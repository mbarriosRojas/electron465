# Historias de usuario — Cargar respuesta de banco (Banfan/Bicentenario/Venezuela) y aplicar cobros

Contexto técnico (para quien implemente):

- Pantalla actual: `/cooperativa/Leer_Txt` ("Pendientes Por Procesar"). Lista lotes generados por "Crear Txt" (`cpp_banco`, `esta=1`) y tiene un botón de acción por fila que hoy abre un modal viejo (`Leer_Txt`/`SubirTxt`/`Ltxt`/`Itxt` en `cooperativa.php` + `mprocesar.php`) — ese modal está **roto** (el binario `Ltxt` no existe, falla en silencio, nunca llega a aplicar nada) y usa una lógica de cruce de contrato **insegura** para bancos que no son Bicentenario (empareja por categoría — banco+empresa+periodicidad+forma+nómina — no por contrato específico, lo que puede aplicar mal un pago cuando el cliente tiene más de un crédito).
- **Esta HU construye una funcionalidad NUEVA y separada**, sin tocar `cooperativa.php`, `mprocesar.php`, ni las tablas `cpp_archivo`/`t_cargar_txt` que usa el flujo viejo. Se agrega como una opción adicional (nuevo botón/modal) en la misma pantalla de `Leer_Txt`, con sus propias tablas y endpoints en `cobranza.php` — mismo patrón que ya se usó para separar `cargaVenezuela` de `cooperativa.php`.
- **Bicentenario y Banfan comparten el mismo formato de archivo de respuesta** (a confirmar con muestra real — ver HU-01, bloqueante). Venezuela ya tiene su propio parser funcionando y probado (`mcargavenezuela.php`, soporta XLSX y CSV legacy) — **no se reescribe**, esta nueva funcionalidad simplemente lo reutiliza como una tercera opción de banco dentro de la misma pantalla unificada.
- **Dirección de las tasas de dólar — no confundir con lo que ya existe:**
  - Para **generar** el archivo que se envía al banco (`Crear Txt` / `TXT Bolívares`) ya usamos **Dólar Cobranza** (HU implementada en `docs/HUs-generar-txt-banfan-bicentenario-dolar-cobranza.md`).
  - Para **leer la respuesta** del banco y aplicar el cobro (esta HU), el usuario pidió explícitamente **Dólar BCV** — es la tasa que el banco realmente usó para cobrarle al cliente en bolívares, así que se usa la tasa BCV de la fecha de cobro, **no** Dólar Cobranza.
- Patrón de cálculo y UI a reutilizar tal cual (ya construido y probado en `cargaVenezuela`/`mcargavenezuela.php`):
  - Resolución de tasa exacta por fecha, sin fallback (`MPrecio_dolar::getPrecioPorFecha($fecha, 'BCV')`), bloqueo total del lote si falta alguna.
  - Auto-asignación de contrato si el cliente tiene exactamente un crédito elegible; "Por Asignar" + selección manual si tiene más de uno; el botón "Procesar" no se habilita hasta que todas las filas tengan contrato.
  - Guardado en una sola operación masiva (`INSERT ... VALUES` multi-fila), no fila por fila.

**Decisiones confirmadas con el usuario:**
- El formato real del archivo de respuesta de Bicentenario/Banfan lo aportó el usuario — ver HU-01 (ya resuelta, con muestras reales de `cobranza_prueba/banfan_respuestas_banco/*.txt`).
- Multi-contrato: mismo patrón ya validado en `cargaVenezuela` (auto-asignar si 1 solo match, "Por Asignar" si hay varios).

---

## HU-01 — Formato real del archivo de respuesta Bicentenario/Banfan (RESUELTA)

Se analizaron 4 archivos reales (`cobranza_prueba/banfan_respuestas_banco/ELECTRON 465 C.A (1-4).txt`). Es un **reporte de texto plano de ancho fijo** ("Listado de Débito de Nómina"), codificación `ISO-8859-1`, separador de línea `\r\n`. Estructura confirmada:

```
BANCO DE LA FUERZA ARMADA NAC ......................................  FECHA  01/07/2026
G-20010657-3 ........................................................  HORA  :   9:25:38
NOML015 .............................................................  PAG...:         1
        Listado de Debito de Nomina de GRUPO ELECTRON 465 C.A
        CONTROL No.: 2606000358  FECHA NOMINA.: 30/06/2026
        E_mail.....: ALVAROHZR5050@GMAIL.COM
====================================================================
  C u e n t a s          Deb/Crd   Nombre               C.I.   *** Observación ***
====================================================================
CUENTAS CON RECHAZOS
01770001491100024103          2.112,00   LOPEZ MENDEZ, MIGUEL      17033223  (POR DISPONIBLE)
... (una línea por cada línea del archivo ENVIADO que el banco no pudo debitar)
Total-> CUENTAS CON RECHAZOS   Hay Cuentas Erradas
 Total Errado------>       719          1.400.091,00
====================================================================
CUENTAS APLICADAS
01770001491100002263          2.112,00   CORTEZ TORRES, WILBERT    24393261
... (una línea por cada línea del archivo ENVIADO que el banco SÍ debitó)
Total-> CUENTAS APLICADAS
 Total Debitado---->     1.361          2.717.979,00
====================================================================
TOTALES DESGLOSADO CORRESPONDIENTE A LA NOMINA: GRUPO ELECTRON 465 C.A
TOTAL(ERRADO+DEBITADO).:     2.080          4.118.070,00
```

**Hallazgos clave que ajustan el diseño:**

1. **Dos secciones de datos**: `CUENTAS CON RECHAZOS` (no se cobró nada, incluye motivo ej. "POR DISPONIBLE") y `CUENTAS APLICADAS` (sí se cobró). Solo la segunda sección genera abonos reales.
2. **Columnas por línea** (separadas por 2+ espacios): `cuenta` (20 dígitos) · `monto` (formato `2.112,00`, punto de miles/coma decimal) · `nombre` (apellidos, nombres) · `cédula` (sin prefijo de letra) · `observación` (solo en rechazos).
3. **Confirma exactamente cómo funciona el "picar"**: si una cuota se fraccionó al generar (ej. en partes de 100 Bs), **cada fragmento aparece como su propia línea repetida** en la respuesta, con el mismo monto. Se probó con el archivo real: sumando todas las líneas de `CUENTAS APLICADAS` por cédula, el total agregado coincide exacto con el "Total Debitado" que reporta el propio archivo (2.717.979,00 Bs, 138 clientes con cobro aplicado en la muestra).
4. **⚠️ Cambio importante de diseño: NO hay fecha de cobro por línea.** Solo existe **una fecha para todo el archivo** (`FECHA NOMINA`, ej. 30/06/2026). Esto es distinto al formato de Venezuela (que sí trae `fechaCobro` por fila). Por lo tanto, para Bicentenario/Banfan la tasa BCV se resuelve **una sola vez por archivo** (con la `FECHA NOMINA`), no por línea — se ajusta HU-04 más abajo.
5. **No hay ningún código/referencia de contrato en la respuesta** — ni el código de empresa (4 dígitos) que se envía, ni el `credito_id`. El único identificador utilizable es la **cédula** (y la cuenta, como dato secundario de validación). Esto confirma que el cruce a contrato específico depende 100% de la lógica de "Por Asignar" (HU-03) cuando el cliente tiene más de un crédito elegible.

**Criterios de aceptación (cumplidos)**
- [x] Formato documentado con muestra real.
- [x] Campo que identifica al cliente: cédula (columna 4, sin prefijo).
- [x] Campo de fecha: una sola por archivo (`FECHA NOMINA`), no por línea.
- [x] Campo de monto: columna 2, formato `#.###,##`.
- [x] Campo de estatus: implícito por sección (`CUENTAS APLICADAS` = cobrado, `CUENTAS CON RECHAZOS` = no cobrado) + texto de motivo en rechazos.
- [x] No existe referencia a contrato específico — confirma que HU-03 es indispensable, no un "nice to have".

---

## HU-02 — Nuevo punto de entrada "Cargar Respuesta de Banco" en Leer_Txt

**Como** usuario de cobranza
**Quiero** un botón/modal nuevo en `/cooperativa/Leer_Txt` para subir la respuesta del banco (Bicentenario, Banfan o Venezuela)
**Para** aplicar los cobros sin depender del flujo viejo que está roto, y sin arriesgar lo que ya funciona

**Criterios de aceptación**
- Nuevo botón visible en la pantalla de `Leer_Txt`, **junto al existente, sin reemplazarlo ni modificarlo** — el botón/flujo viejo (ligado a `cpp_banco`/`Crear Txt`/"Generar") sigue intacto y funcionando como hoy.
- Selección de banco: `BICENTENARIO`, `BANFAN`, `VENEZUELA`.
- Si se elige Venezuela, la subida delega en el parser ya existente (`mcargavenezuela.php`) — incluye validación de extensión (.xlsx/.csv) igual que hoy en `cargaVenezuela`.
- Si se elige Bicentenario/Banfan, usa el parser nuevo de la HU-03.
- Tablas nuevas y propias para este flujo (ej. `t_archivos_respuesta_banco`, `t_cargar_respuesta_banco`), **no se reutiliza** `cpp_archivo`/`t_cargar_txt` del flujo viejo.
- Validación de archivo duplicado: por nombre de archivo + banco (igual patrón que ya existe en `cargaVenezuela`/`t_archivos_venezuela`), bloqueando reprocesar el mismo archivo dos veces.

---

## HU-03 — Parsear la respuesta, agrupar por cliente, y resolver a qué contrato pertenece

**Como** sistema
**Quiero** leer el archivo (Bicentenario/Banfan), quedarme solo con las líneas de `CUENTAS APLICADAS`, sumarlas por cédula, y encontrar el contrato específico
**Para** poder aplicar el pago correctamente incluso cuando el cliente tiene varios créditos o cuando una misma cuota vino fraccionada ("picada") en varias líneas

**Criterios de aceptación**
- Se parsean ambas secciones (`CUENTAS CON RECHAZOS` y `CUENTAS APLICADAS`), separando líneas por 2+ espacios en blanco: `cuenta`, `monto`, `nombre`, `cédula`, `observación` (esta última solo en rechazos).
- **Solo las líneas de `CUENTAS APLICADAS` generan abono** — las de `CUENTAS CON RECHAZOS` se muestran informativamente (ver HU-05) pero no se procesan como pago.
- Antes de calcular el contrato, se **agrupan y suman todas las líneas aplicadas por cédula** (una cuota fraccionada en 10 líneas de 100 Bs debe sumar como un solo abono de 1000 Bs para ese cliente, no como 10 abonos separados).
- Búsqueda de contratos elegibles: créditos del cliente con `cobrado_en` = banco correspondiente, `cantidad != 0`, `marca_consulta != 6`, saldo pendiente (`resta > 0`) — **sin** el filtro de estado `oide` (ya eliminado globalmente, ver `docs/HUs-generar-txt-banfan-bicentenario-dolar-cobranza.md`).
- Si hay exactamente **un** contrato elegible → se asigna automáticamente.
- Si hay **cero** o **más de uno** → la fila queda "Por Asignar", igual que en `cargaVenezuela`: el usuario debe abrir un combo y elegir manualmente antes de poder procesar el lote completo.
- **No existe ningún código de referencia a contrato en este formato de respuesta** (confirmado en HU-01) — la cédula es el único cruce posible, así que esta pantalla de "Por Asignar" no es opcional, es el mecanismo principal para clientes con más de un crédito.

---

## HU-04 — Convertir el monto de Bs a USD usando la tasa BCV del archivo

**Como** sistema
**Quiero** dividir el monto en bolívares (ya sumado por cliente) entre la tasa BCV vigente para la fecha del archivo
**Para** registrar el abono en dólares, que es como vive el saldo del cliente en la plataforma

**Criterios de aceptación**
- **Para Bicentenario/Banfan**: el archivo trae una sola fecha (`FECHA NOMINA`) para todas sus líneas — la tasa BCV se resuelve **una vez por archivo**, no por línea (a diferencia de Venezuela, que sí tiene fecha por fila y ya usa tasa por fila).
- `MPrecio_dolar::getPrecioPorFecha($fechaNomina, 'BCV')`, sin fallback a fecha cercana.
- `monto_usd = monto_bs_total_del_cliente / tasa_bcv(fecha_nomina)`.
- Si falta la tasa BCV de la fecha del archivo, se bloquea la lectura **completa** (no se puede ni previsualizar) — mensaje claro con la fecha faltante y enlace a Panel → Precio del Dólar.
- La descripción que se guarda en `t_lista_cobros` sigue el formato exacto pedido:
  `"<monto_bs formateado> BS REF <tasa_bcv formateada> BCV"`, ejemplo: `1.786,00 BS REF 94,00 BCV`.

---

## HU-05 — Grilla de previsualización antes de procesar (validación visual manual)

**Como** usuario de cobranza
**Quiero** ver una lista previa de lo que se va a aplicar (cliente, contrato, monto, fecha, descripción) antes de confirmar
**Para** revisar que todo esté correcto antes de tocar los saldos reales de los clientes

**Criterios de aceptación**
- Tras leer el archivo, se muestra una grilla con columnas: **Cliente** (cédula/nombre), **Contrato**, **Monto** (Bs y USD), **Fecha** (de cobro para Venezuela; fecha del archivo/nómina para Bicentenario/Banfan), **Descripción** (el texto exacto que se va a guardar).
- La grilla solo lista clientes de `CUENTAS APLICADAS` (los que sí se cobraron) — los de `CUENTAS CON RECHAZOS` se muestran aparte, en una segunda pestaña/sección informativa de solo lectura (motivo del rechazo incluido), sin afectar el botón "Procesar".
- Filas "Por Asignar" se destacan visualmente (mismo estilo ya usado en `cargaVenezuela`: color distintivo + texto "Por Asignar").
- El botón **"Procesar"** solo se habilita cuando **todas** las filas de `CUENTAS APLICADAS` tengan contrato asignado — igual que ya se exige en `cargaVenezuela` antes de "Guardar Cambios".
- Nada se escribe en `t_lista_cobros` hasta que el usuario presiona "Procesar" explícitamente.

---

## HU-06 — Procesar el lote completo de forma masiva (5,000–10,000+ registros)

**Como** usuario de cobranza
**Quiero** que al presionar "Procesar" se apliquen todos los pagos de una sola vez, con una pantalla de carga hasta terminar
**Para** no tener que esperar registro por registro ni arriesgar que el proceso quede a medias

**Criterios de aceptación**
- El guardado se hace con una **sola operación masiva** (`INSERT INTO t_lista_cobros (...) VALUES (...), (...), ...`), no una consulta por cliente — mismo patrón ya usado en `guardarLote()` de `mcargavenezuela.php`, que ya se probó con miles de filas sin problema.
- El indicador de carga permanece visible **hasta que el servidor responda con el resultado final** de todo el lote (timeout del `$.ajax` ampliado, ej. 180000ms, igual que ya se hace en `crear_txt_bs.js`).
- Al terminar, se muestra un resumen: cantidad de registros procesados, monto total en Bs y en USD, y cuántas filas (si alguna) no se pudieron aplicar y por qué.
- El archivo queda marcado como procesado (no se puede volver a aplicar el mismo archivo dos veces — mismo criterio de bloqueo por `estatus`/carrera ya implementado en `guardarLote()`).

---

## Fuera de alcance

- No se modifica nada del flujo viejo (`cooperativa.php::Leer_Txt/Ver_Txt/SubirTxt/Ltxt/Itxt`, `mprocesar.php`, tablas `cpp_archivo`/`t_cargar_txt`) — sigue existiendo tal cual, roto, hasta que se decida qué hacer con él por separado.
- No se reescribe el parser de Venezuela (`mcargavenezuela.php`) — se reutiliza tal cual.
- No se implementa un mecanismo de reversión/deshacer un lote ya procesado — si se comete un error, se corrige manualmente (mismo nivel de las funcionalidades ya existentes).
