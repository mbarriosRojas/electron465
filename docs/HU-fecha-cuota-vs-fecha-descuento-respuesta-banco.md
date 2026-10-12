# Historia de usuario — FECHA CUOTA real vs. FECHA DESCUENTO del banco al guardar "Respuesta de Banco"

Contexto técnico (para quien implemente):

- Pantalla: `http://localhost:8000/cobranza/respuestaBanco` → "Archivos Pendientes" → al asignar contratos y presionar "Guardar" se ejecuta `Mrespuestabanco::guardarLote($filas, $usuarioId)` (`system/application/models/cargar/mrespuestabanco.php`), que inserta en `t_lista_cobros`.
- El modal **"Detalles de Crédito (Estado de Cuenta)"** → pestaña **"Historial de Cuotas"** (`CClientes::CI_Lista_Cobros()`, `system/application/models/cclientes.php:267`) lee directo de `t_lista_cobros` y muestra:
  - **FECHA CUOTA** = columna `t_lista_cobros.fecha` (`cclientes.php:340`, celda `$row->fecha`).
  - **FECHA DESCUENTO** = columna `t_lista_cobros.farc` (`cclientes.php:340`, celda `$row->farc`).
- **El problema:** en `guardarLote()` (línea ~666 y ~740 según el bloque de inserción), tanto `fecha` como `farc` se llenan con el **mismo valor**: `$fecha = $arch->fecha_nomina` (la fecha de nómina que trae el TXT de respuesta del banco). Es decir, hoy **FECHA CUOTA y FECHA DESCUENTO siempre muestran la misma fecha**, y esa fecha es la del banco, no la fecha real en que correspondía cobrar la cuota del contrato.

**Confirmado con datos reales de producción** (archivo BANFAN, nómina `fecha_nomina = 2026-06-30`):

| documento_id | credito_id | FECHA CUOTA (actual, incorrecta) | FECHA DESCUENTO (actual) | `fecha_inicio_cobro` del contrato |
|---|---|---|---|---|
| 23611633 | N-00001967 | 2026-06-30 | 2026-06-30 | 2025-04-**28** |
| 17601906 | N-00002994 | 2026-06-30 | 2026-06-30 | 2025-10-**28** |
| 30614646 | N-00002226 | 2026-06-30 | 2026-06-30 | 2025-06-**28** |

Los tres contratos cobran el día **28** de cada mes (según `t_clientes_creditos.fecha_inicio_cobro`), pero hoy el sistema les registra la cuota como si hubiera vencido el día 30 (fecha del banco), no el 28 (fecha real de la cuota).

**Objetivo de negocio (tal como lo explicó el usuario):** al guardar el lote de "Respuesta de Banco", el sistema debe:
- **FECHA CUOTA** = combinar el **día** de `fecha_inicio_cobro` del contrato (el día del mes en que ese contrato cobra) con el **mes/año** que se le está cobrando según la nómina del TXT (`fecha_nomina`) — es decir, "pegarle" ese día al mes de cobro.
- **FECHA DESCUENTO** = se mantiene igual a hoy: la fecha real que reporta el banco en el TXT (`fecha_nomina`), sin modificar.

---

## HU-01 — Calcular FECHA CUOTA combinando el día del contrato con el mes de cobro de la nómina

**Como** sistema de cobranza
**Quiero** que la FECHA CUOTA registrada en `t_lista_cobros.fecha` use el día real de cobro del contrato (no el día que reporta el banco)
**Para** que el Historial de Cuotas del cliente refleje cuándo vencía realmente esa cuota, y no confundirla con la fecha en que el banco procesó el descuento

**Criterios de aceptación**

- En `Mrespuestabanco::guardarLote()`, para cada fila a insertar:
  1. Obtener `fecha_inicio_cobro` de `t_clientes_creditos` para el `credito_id` (contrato) de esa fila.
  2. Extraer el **día** de `fecha_inicio_cobro` (ej. si es `2025-04-28`, el día es `28`).
  3. Extraer el **mes y año** de `fecha_nomina` del archivo (ej. `2026-06-30` → mes `06`, año `2026`).
  4. Construir la nueva `fecha` (FECHA CUOTA) como `AAAA-MM-DD` con ese mes/año y ese día — ej. `2026-06-28`.
- `farc` (FECHA DESCUENTO) **no cambia**: sigue siendo `fecha_nomina` tal cual hoy.
- `mesp`/`anop` (mes/año de cobro usados en otros reportes) **no cambian**: siguen derivándose de `fecha_nomina`, ya que representan el mes que se está cobrando (que coincide con el mes/año de la FECHA CUOTA recién construida).
- Aplica a **todas** las filas que se insertan en el lote, incluyendo las que resultaron de la distribución en cascada entre varios contratos (`distribuirEntreContratosMultiples`) — cada porción usa el `fecha_inicio_cobro` de **su propio** contrato asignado.

**Manejo de bordes (obligatorio, no opcional):**

- Si el día de `fecha_inicio_cobro` **no existe** en el mes de cobro (ej. día 31 en un mes de 30 días, o día 29/30/31 en febrero), usar el **último día válido de ese mes** (mismo criterio de "clamping" ya usado en `gPlanCobro()`/`normalizarFechaCorte()` del generador de TXT, para mantener consistencia con el resto del sistema).
- Si `fecha_inicio_cobro` es nula, vacía o inválida (`0000-00-00`), usar como respaldo el comportamiento actual (FECHA CUOTA = `fecha_nomina`), para no romper el guardado por falta de dato.

**Caso de prueba concreto (con datos reales, ya verificados en esta conversación):**
Archivo BANFAN con `fecha_nomina = 2026-06-30`, contrato `N-00001967` (`fecha_inicio_cobro = 2025-04-28`) → después del cambio, esa fila debe guardar `fecha = 2026-06-28` (FECHA CUOTA) y `farc = 2026-06-30` (FECHA DESCUENTO, sin cambios).

---

## HU-02 — Verificación visual en el Estado de Cuenta

**Como** usuario de cobranza
**Quiero** ver claramente distintas la FECHA CUOTA y la FECHA DESCUENTO en el Historial de Cuotas
**Para** entender cuándo vencía la cuota versus cuándo el banco realmente la descontó

**Criterios de aceptación**

- No se requiere cambio de UI: el modal "Detalles de Crédito → Historial de Cuotas" ya muestra ambas columnas (`cclientes.php`) — solo cambia el **dato** que llega a `fecha` gracias a HU-01.
- QA: abrir el Estado de Cuenta de al menos uno de los contratos de la tabla de ejemplo (ej. `N-00001967`) después de reprocesar/guardar y confirmar que FECHA CUOTA = 28/06/2026 y FECHA DESCUENTO = 30/06/2026 (fechas distintas, ya no iguales).

---

## Nota fuera de alcance (no incluida en esta HU, solo para contexto)

El flujo de **Carga Venezuela** (`Mcargavenezuela::guardarLote()`) tiene una construcción similar de `fecha`/`farc`, pero ahí `fecha` ya se resuelve **por fila** (fecha de cobro individual del TXT de Venezuela, no una única fecha de nómina por archivo), por lo que el impacto es distinto y no se cubre en esta HU. Si se desea aplicar el mismo criterio (día del contrato + mes de cobro) a Venezuela, debe evaluarse como una HU aparte.
