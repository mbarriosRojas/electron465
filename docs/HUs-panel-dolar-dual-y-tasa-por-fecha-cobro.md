# Historias de usuario — Dólar Cobranza + Tasa por fecha de cobro en carga Venezuela

Contexto técnico (para quien implemente):

- Panel actual: `panel.php` (`panelDolar`, `getPrecioDolarEstado`, `guardarPrecioDolar`, `getPrecioDolarVigente`) + modelo `mprecio_dolar.php` (`MPrecio_dolar`), tablas `t_precio_dolar` (tasa vigente por día) y `t_precio_dolar_bitacora` (histórico). Hoy solo existe **un** tipo de tasa (BCV), sin columna que distinga tipos.
- Consumidores actuales de `t_precio_dolar` / `MPrecio_dolar` (los 4 puntos que hay que revisar al tocar el esquema):
  1. `panel.php` — el panel mismo (alta/consulta).
  2. `mgtxt_bolivares.php` (`GtxtBs`) — genera TXT Bs. para bancos genéricos, usa `getPrecioActivo()` → `getUltimoRegistroVigente()`.
  3. `mgtxt_venezuela.php` (`generar()` / `exportarXls()`) — genera TXT/XLS de domiciliación BDV, mismo patrón `getPrecioActivo()` → `getUltimoRegistroVigente()`.
  4. `mcargavenezuela.php` (`resolverTasaPorFecha()` → `getPrecioPorFecha($fecha)`) — usado por `registrar()`, `registrarOReemplazar()`, `guardarLote()` y el preview `cobranza::getTasaPorFecha`.
- `getPrecioPorFecha($fecha)` (`mprecio_dolar.php:119-144`) es de **coincidencia exacta**: busca `t_precio_dolar` por esa fecha con `precio > 0`, si no hay cae a `t_precio_dolar_bitacora` por la misma fecha exacta, si no hay devuelve `null`. **No** hay fallback a fecha más cercana ni a "última tasa activa".
- Carga Venezuela hoy (`mcargavenezuela.php`): `grid()` (líneas 661-749) y `guardarLote()` (líneas 757-846) usan **una sola tasa por archivo completo**, resuelta a partir de `fenv` (fecha de envío) y guardada en `t_archivos_venezuela.precio_dolar` / `fecha_tasa`. Esa misma tasa se aplica a **todas** las filas del lote sin importar la fecha de cobro (`fpro`) de cada fila.
- `t_archivos_venezuela.precio_dolar` / `fecha_tasa` existen vía `ensureSchema()` (ALTER defensivos) y no hay `CREATE TABLE` formal — son tablas legacy.
- **Nota:** `cooperativa.php` / `cooperativa_.php` tienen versiones antiguas y muertas de estos endpoints (las rutas activas en `routes.php` apuntan a `cobranza.php`). No hace falta tocarlas salvo que se confirme lo contrario.

---

## HU-01 — Registrar dos tipos de tasa en Panel del Dólar (BCV + Cobranza)

**Como** usuario administrador
**Quiero** registrar en el mismo panel (`/cooperativa/panelDolar`) dos tasas independientes por día: **Dólar BCV** (la actual) y **Dólar Cobranza**
**Para** tener ambas tasas disponibles para los distintos cálculos del sistema sin mezclarlas

**Criterios de aceptación**
- La pantalla muestra dos campos de captura separados y claramente etiquetados: "Dólar BCV" y "Dólar Cobranza".
- **Es obligatorio registrar ambos valores en la misma acción de guardado** — si falta uno de los dos, el sistema no guarda ninguno y muestra mensaje indicando cuál falta.
- Al guardar, se crean/actualizan **dos registros independientes** (uno por tipo) para el día de hoy, cada uno con su propia bitácora histórica.
- El histórico (`resumen por día` y `bitácora completa`) muestra ambos tipos, identificando claramente a cuál corresponde cada fila (columna "Tipo": BCV / Cobranza).
- El estado del panel (pendiente de actualización, precio activo para hoy, etc.) se evalúa **por separado para cada tipo** — puede faltar solo uno y el panel debe advertirlo puntualmente (ej. "Falta registrar Dólar Cobranza de hoy").

**Cambios técnicos esperados**
- `t_precio_dolar` y `t_precio_dolar_bitacora`: agregar columna `tipo` (ej. `ENUM('BCV','COBRANZA') NOT NULL DEFAULT 'BCV'`). Los registros históricos existentes quedan como `BCV` por default (no requieren migración de datos, ya que hasta hoy solo existía ese tipo).
- Índice único actual `uk_precio_dolar_fecha (fecha)` debe pasar a `(fecha, tipo)` para permitir una fila por fecha y por tipo.
- `MPrecio_dolar`: todos los métodos (`getPrecioActivo`, `getPrecioPorFecha`, `getEstadoPanel`, `guardar`, `getBitacora`, `getResumenPorDia`, `getUltimoRegistroVigente`) deben aceptar un parámetro `$tipo` (default `'BCV'` para no romper los 4 consumidores existentes que hoy no pasan tipo — ver contexto arriba).
- `panel.php`: `guardarPrecioDolar()` debe recibir y validar **ambos** precios en un solo POST (`precio_bcv`, `precio_cobranza`), rechazando si falta cualquiera, y llamar dos veces a `MPrecio_dolar->guardar()` (una por tipo) dentro de la misma operación.
- Vista `panel/panelDolar.php` + `panelDolar.js`: dos inputs, dos bloques de estado, tabla de histórico con columna Tipo.

**Decisión confirmada:** "Dólar Cobranza" se usará más adelante para generar los TXT que se envían al banco (`GtxtBs` / `Mgtxt_venezuela` / `Mgtxt_bolivares`); ese consumo **no se desarrolla en esta HU** — por ahora solo se registra y queda disponible en `t_precio_dolar`/bitácora con `tipo='COBRANZA'`, lista para que un HU futuro la conecte a esos generadores de TXT sin cambios de esquema adicionales.

**Otras notas**
- Se permite editar/corregir la tasa de un tipo sin tocar el otro (son independientes; cada `guardar()` actúa sobre su propio `tipo`).

---

## HU-02 — Calcular Monto USD en "Archivos Pendientes" (carga Venezuela) usando la tasa BCV de la fecha de cobro del banco, por fila

**Como** usuario de cobranza
**Quiero** que el Monto USD de cada fila en la grilla de "Archivos Pendientes" (`cargaVenezuela` → Ver Archivos Pendientes) se calcule con la tasa BCV vigente en la **fecha en que el banco procesó/cobró esa transacción puntual** (columna `fechaCobro` del XLSX del banco, campo `fpro` en `t_cargar_txt_venezuela`)
**Para** que el monto en dólares refleje la tasa real del día del cobro y no la del día de envío del archivo completo

**Contexto del caso real:** en la respuesta del banco (ej. `nevado gas julio 26.xlsx`), cada fila tiene su propia fecha de cobro. Hoy el sistema usa **una sola tasa para todo el archivo** (la de la fecha de envío, `fenv`), ignorando que cada fila puede tener una fecha de cobro distinta.

**Criterios de aceptación**
- En `grid()` (listado de "Archivos Pendientes"), el "Monto USD" de cada fila se calcula como `monto_bs / tasa_bcv(fpro_de_esa_fila)`, **no** con la tasa única del archivo.
- La leyenda de la grilla dejar de mostrar una única "Tasa BCV del envío" global y en su lugar debe indicar que la tasa aplicada varía por fila según su fecha de cobro (o mostrar un rango si todas las filas caen en la misma fecha).
- Al presionar "Guardar Cambios" (`guardarLote`), el monto USD que se inserta en `t_lista_cobros` para cada fila usa la tasa BCV de **esa fila específica** (`fpro`), no la tasa global del archivo.
- **Decisión confirmada: si una o más filas no tienen tasa BCV registrada para su fecha de cobro exacta, se bloquea el guardado de TODO el lote** hasta que todas las filas tengan su tasa — mensaje explícito indicando qué fila(s)/cédula(s) y qué fecha(s) de cobro faltan. No se guarda nada parcial.
- El campo `t_archivos_venezuela.precio_dolar` / `fecha_tasa` (tasa por fecha de envío) se conserva sin cambios — sigue sirviendo para el mensaje de "no se puede subir el archivo sin tasa de envío" en `subirVenezuela` (HU-01 del documento `HUs-cobranza-respuesta-bs-usd.md`), que no se modifica. Este HU solo cambia el cálculo del Monto USD **dentro** de la grilla de pendientes y en el guardado del lote.

**Cambios técnicos esperados**
- `grid($oida)`: por cada fila, llamar `resolverTasaPorFecha($fila->fpro)` (tipo BCV) en vez de usar el `$tasa` fijo del archivo (líneas 663-664 / 696).
- `guardarLote($filas, $usuarioId)`: eliminar la resolución de tasa única antes del loop (líneas 774-787) y resolver la tasa **dentro** del loop por fila usando su `fpro`/`fechaCobro` (línea 815 pasa a depender de esta tasa por fila). `construirDescripcionCobro()` debe recibir la tasa de esa fila puntual para que la descripción en `t_lista_cobros` sea auditable (actualmente embebe una tasa única para todo el lote).
- Nueva validación previa al guardado: recorrer todas las filas y verificar que cada `fpro` tenga tasa BCV registrada **antes** de insertar nada (fail-fast, todo o nada, igual que ya se hace hoy con "filas sin contrato asignado").

**Notas de implementación**
- Sin fallback a `fenv`/tasa del archivo: cada fila exige tasa BCV exacta para su propio `fpro`, consistente con el bloqueo total del lote descrito arriba (no tendría sentido mezclar fallback con bloqueo estricto).
- El cambio de cálculo aplica a la vista (`grid()`) y al guardado (`guardarLote()`); las exportaciones (`Genera_Xls_Json`) heredan el mismo Monto USD ya calculado en la grilla, así que quedan cubiertas sin cambios adicionales.

---

## Fuera de alcance (ambas HU)

- No se migran datos históricos ya guardados en `t_lista_cobros` con la tasa única anterior — el cambio aplica solo hacia adelante, a nuevos lotes.
- No se modifica el flujo de generación de TXT/XLS de domiciliación (`mgtxt_venezuela.php`, `mgtxt_bolivares.php`) — siguen usando la tasa BCV activa como hoy, salvo que HU-01 defina un consumidor explícito para "Dólar Cobranza".
- No se tocan `cooperativa.php` / `cooperativa_.php` (código muerto, rutas no activas según `routes.php`).
