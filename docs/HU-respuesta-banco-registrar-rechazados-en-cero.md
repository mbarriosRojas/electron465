# Historia de usuario — Registrar en bitácora los cobros rechazados por el banco (monto en 0)

Contexto técnico (para quien implemente):

- Pantalla: `Crear_Txt` → **Respuesta Banco** (`/cobranza/respuestaBanco`), modelo `mrespuestabanco.php`.
- Hoy, al leer el archivo del banco (`leerArchivoDetalle()`, línea 183), cada línea se clasifica en `t_cargar_respuesta_banco` con `seccion`:
  - `'A'` (Aplicada): sí se cobró, aparece en la grilla editable "Aplicados/Por Asignar" (`grid()`, línea 656) y al presionar "Guardar" (botón genérico de TGrid Editable) se procesa en `guardarLote()` (línea 1024), que inserta un renglón por cliente en **`t_lista_cobros`** — la tabla que alimenta el historial real de cobros del cliente (`cclientes.php::CI_Lista_Cobros()`, la pestaña "Historia de Cuotas" del Estado de Cuenta).
  - `'R'` (Rechazada): el banco no cobró (sin disponibilidad u otro motivo). Hoy **solo** se guarda en `t_cargar_respuesta_banco` con su `motivo` (texto del banco) y se muestra en `gridRechazos()` (línea 770) como grilla de **solo lectura, informativa**. **Nunca se inserta nada en `t_lista_cobros`** — por eso hoy, si se mira el historial de cuotas del cliente, ese día simplemente no aparece ningún intento de cobro.
- `guardarLote()` (línea 1024) recibe `$filas` desde el frontend (solo las filas `'A'` que la grilla editable tiene cargadas) e inserta en bloques de 500 dentro de una transacción (`insertarEnBloques()`, ya implementado) — mecanismo a reutilizar, no a duplicar.
- Asignación de contrato para las filas `'A'`: `autoAsignarContratosUnicos()` (línea 400) y `marcarSinSaldo()` (línea 459) — ambas filtran explícitamente `WHERE ... seccion = 'A'`. Las filas `'R'` **nunca pasan por esta asignación** hoy — no tienen `contrato` resuelto.

**Objetivo de negocio (tal como lo explicó el usuario):** cuando el banco rechaza el cobro de un cliente (sin disponibilidad ese día), igual debe quedar un registro en la bitácora de cobros del cliente con **monto 0** y un motivo identificable (ej. `(POR DISPONIBLE)`) — para que al revisar el historial se sepa que ese día el sistema **sí intentó cobrarle**, pero no había fondos, en vez de no decir nada (lo cual hoy se puede confundir con "nunca se le intentó cobrar").

---

## HU-01 — Asignar contrato también a las filas rechazadas

**Como** sistema
**Quiero** resolver el contrato correspondiente a cada cliente rechazado, igual que se hace hoy con los aplicados
**Para** poder generar su registro de cobro en 0 contra el contrato correcto

**Criterios de aceptación**
- Extender (o duplicar con `seccion='R'`) la misma lógica de `autoAsignarContratosUnicos()` y `marcarSinSaldo()` para las filas `seccion='R'` del archivo (`oida`) — mismas reglas: si el cliente tiene exactamente un contrato con saldo pendiente bajo este banco (o, en su defecto, en cualquier banco), se le asigna; si no tiene ningún contrato con saldo pendiente, se marca `SIN_SALDO` (igual que hoy para los aplicados).
- Si un cliente rechazado tiene **2+ contratos** con saldo pendiente (ambiguo, como ya pasa hoy con los aplicados vía "Por Asignar"), debe quedar visible para asignación manual — no se le puede adivinar el contrato.
- Esto corre automáticamente al leer el archivo (`leerArchivoDetalle()`), igual que hoy corre para los aplicados — no requiere un paso manual aparte.

---

## HU-02 — Incluir los rechazados (con contrato resuelto) en el "Guardar" del lote

**Como** sistema
**Quiero** que al guardar el lote también se inserte un registro en `t_lista_cobros` para cada cliente rechazado con contrato ya resuelto
**Para** que su historial de cobros muestre el intento de cobro de ese día, con monto 0

**Criterios de aceptación**
- En `guardarLote()`, además de procesar `$filas` (los aplicados que manda el frontend), la función debe consultar directamente en base de datos las filas `seccion='R'` del mismo `oida` que **ya tengan contrato asignado** (no `SIN_SALDO`, no ambiguas) — no depende de que el frontend las envíe, ya que la grilla de rechazados es de solo lectura.
- Por cada una, insertar un renglón en `t_lista_cobros` con:
  - `monto` = **0** (no se cobró nada realmente — el monto que aparecía en el archivo del banco para esa fila era el *intento*, no lo cobrado).
  - `mes` = mismo formato que las aplicadas (`'RESPUESTA ' . $banco . ':' . $creditoId`), para mantener consistencia con los reportes que ya parsean ese campo.
  - `descripcion` = igual que `construirDescripcion()` pero identificable como rechazo — ej. `(POR DISPONIBLE)` o `SIN DISPONIBILIDAD — $motivoDelBanco` (usar el `motivo` real que trajo el banco, guardado en `t_cargar_respuesta_banco.motivo`, para que quede trazable *por qué* lo rechazó, no solo que lo rechazó).
  - `fecha` (FECHA CUOTA) y `farc` (FECHA DESCUENTO) — mismas reglas que ya usa `guardarLote()` para las aplicadas (línea ~1089-1094), reutilizando `obtenerDiasCobroPorContrato()` / `construirFechaCuota()`.
- Estos registros en 0 se insertan usando el mismo `insertarEnBloques()` (misma transacción que las aplicadas) — si algo falla, se revierte todo el lote (aplicados + rechazados), no queda una carga parcial.
- El mensaje de éxito (`msg` de retorno) debe indicar cuántos registros en 0 se generaron, además del conteo de cobros aplicados (ej. *"Se registraron 45 cobro(s) aplicados y 8 rechazado(s) en 0 (sin disponibilidad). Total Bs.: ..."*).

---

## HU-03 — No duplicar ni bloquear por los rechazados excluidos

**Como** sistema
**Quiero** que los rechazados `SIN_SALDO` o ambiguos (2+ contratos) NO generen registro en `t_lista_cobros`
**Para** no inventar un cobro en 0 contra un contrato que no se pudo determinar con certeza

**Criterios de aceptación**
- Un rechazado `SIN_SALDO` (cliente sin ningún contrato con saldo pendiente) se excluye igual que hoy se excluyen los aplicados `SIN_SALDO` — no bloquea el resto del lote, simplemente no genera registro.
- Un rechazado con contrato ambiguo (2+ créditos con saldo) tampoco genera registro hasta que se asigne manualmente (fuera de alcance de esta HU decidir cómo se asigna manualmente un rechazo — ver "Fuera de alcance").
- Reintentar "Guardar" sobre un archivo ya procesado (`estatus=1`) debe seguir bloqueado igual que hoy (`guardarLote()` ya lo valida) — esto aplica al lote completo (aplicados + rechazados en 0), no se pueden procesar por separado.

---

## Fuera de alcance

- No se agrega una pantalla/flujo para asignar manualmente el contrato de un cliente **rechazado** con 2+ créditos ambiguos — hoy eso ya no existe ni para los aplicados de forma dedicada (se resuelve por la grilla "Por Asignar"); si se necesita, es una HU aparte.
- No se cambia el texto ni el comportamiento de `gridRechazos()` (la grilla informativa) — sigue mostrando lo mismo que hoy, esta HU solo agrega el efecto en `t_lista_cobros`.
- No se modifica `leerArchivoDetalle()` para volver a leer/reprocesar archivos ya cargados — la asignación de contrato a rechazados (HU-01) corre en la misma pasada de lectura que ya existe hoy.
- No se decide aquí el texto exacto final de `(POR DISPONIBLE)` vs. incluir el motivo textual del banco — se deja como sugerencia en HU-02; confirmar con el usuario el formato exacto antes de implementar si hay dudas.
