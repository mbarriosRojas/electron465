# Historias de usuario — Separar "Activos" (cuota del mes) vs "Morosos" (deuda anterior) en TXT Bolívares

Contexto técnico (para quien implemente):

- Pantalla: `Crear_Txt` → modal "Generar TXT en Bolívares" (`crear_txt_bs.js` + `reportes/form_archivos_crear_bs.php`), backend `mgtxt_bolivares.php::GtxtBs()` → `GtxtGenerator::generarBolivaresConCorte()` (`gtxt_generator.php:282`).
- Hoy (después del ajuste reciente confirmado por el usuario) `calcularMonto()` (`gtxt_generator.php:518`) hace lo siguiente para el tipo 'C'/'M': camina el cronograma de cuotas (`gPlanCobro()`, `gtxt_generator.php:572`) acumulando lo vencido **hasta la fecha de corte** (`$cutoff`), y si el cliente ya está al día según el cronograma pero todavía tiene saldo total pendiente (`$row->resta > 0`), igual cobra la cuota del ciclo actual. Es decir: **hoy el sistema mezcla en un solo archivo tanto la cuota del mes actual como cualquier atraso acumulado de meses anteriores.**
- **Esta HU separa esos dos conceptos en dos modos de generación distintos**, seleccionables por el usuario, sin duplicar cobros entre ambos:
  - **Activos**: cobra ÚNICAMENTE la cuota que corresponde al mes seleccionado (ni más, ni menos), sin importar si el cliente tiene atrasos de meses anteriores. Si ese mes específico ya está pagado, no se incluye.
  - **Morosos**: cobra ÚNICAMENTE lo que quedó pendiente de meses **anteriores** al seleccionado (si eliges Julio, busca deuda de Junio hacia atrás) — **no incluye la cuota del mes seleccionado**. Si el cliente está al día hasta el mes anterior, no se incluye.
- `normalizarFechaCorte()` (`gtxt_generator.php:643`) ya resuelve "hasta fin de mes" para los presets "SEMANAL"/"MENSUAL" del selector de fecha (`dia <= 1`) — se reutiliza tal cual para calcular el fin del mes seleccionado y el fin del mes anterior.
- El formato del archivo (51/54 caracteres) no cambia — esta HU solo cambia CUÁLES contratos entran y CUÁNTO se calcula por cada uno, en dos archivos/generaciones separadas.

**Objetivo de negocio (tal como lo explicó el usuario):** dividir el envío al banco en dos flujos — uno para la cobranza normal del mes en curso ("Activos"), y otro exclusivamente para gestionar cartera vencida de meses viejos ("Morosos") — en vez de mandar todo mezclado como hoy.

---

## HU-01 — Selector "Activos / Morosos" en el modal TXT Bolívares

**Como** usuario de cobranza
**Quiero** elegir entre "Activos" y "Morosos" al generar el TXT en Bolívares
**Para** separar la cobranza del mes en curso de la gestión de cartera vencida

**Criterios de aceptación**
- Nuevo campo `<select>` "Tipo de Cliente" (o "Filtro") en `form_archivos_crear_bs.php`, con opciones `ACTIVOS` (default) y `MOROSOS`, ubicado junto al campo "Hasta la Fecha" ya existente.
- El valor elegido se envía como nuevo parámetro (ej. `filtro_cliente`) en `crear_txt_bs.js` → `GtxtBs` → `generarBolivaresConCorte()`.
- La leyenda/mensaje de resultado indica claramente cuál modo se usó (ej. "Modo: Activos — cuota de Julio 2026" o "Modo: Morosos — deuda hasta Junio 2026").
- Campo obligatorio: si no se selecciona, no se puede generar (evitar ambigüedad).

---

## HU-02 — Cálculo "Activos": solo la cuota del mes seleccionado

**Como** sistema
**Quiero** cobrar exclusivamente la cuota que corresponde al mes elegido en "Hasta la Fecha"
**Para** que el archivo de cobranza normal no incluya atrasos de meses anteriores

**Criterios de aceptación**
- Nueva función `calcularMontoActivos($row, $anoMes)` en `gtxt_generator.php`: camina el cronograma (`gPlanCobro`) y busca **únicamente** la(s) cuota(s) cuya fecha programada cae **dentro del mes/año seleccionado** (mismo mes y año que "Hasta la Fecha", sin importar el día exacto).
- Si esa cuota del mes ya fue pagada (según `total_pagado` acumulado hasta ese punto del cronograma), no se cobra (monto 0, se excluye del archivo).
- Si esa cuota está parcialmente pagada, se cobra solo el saldo restante de esa cuota puntual (nunca el acumulado de meses anteriores).
- Si el contrato no tiene ninguna cuota programada en ese mes (ej. el contrato ya terminó antes, o empieza después), se excluye — no es un error, simplemente no aplica ese mes.
- **No se debe usar** el "cobro igual si está al día" que hoy aplica de forma general en `calcularMonto()` combinado con acumulación — en modo Activos NUNCA se acumulan cuotas de otros meses, sea cual sea el estado de mora del cliente.

**Caso de prueba concreto (ya validado en conversación previa, reutilizar como QA):** Bicentenario, Grupo, nómina TROMERCA (TROLEBUS MERIDA C.A), Único, Mensual, mes Julio 2026 → deben aparecer únicamente los contratos cuya cuota cae en julio, con el monto de esa cuota puntual (no el acumulado histórico).

---

## HU-03 — Cálculo "Morosos": solo deuda estrictamente anterior al mes seleccionado

**Como** usuario de cobranza
**Quiero** un archivo separado que solo incluya a quienes deben cuotas de meses **antes** del seleccionado
**Para** gestionar cartera vencida sin mezclarla con la cobranza normal del mes

**Criterios de aceptación**
- Nueva función `calcularMontoMorosos($row, $cutoffMesAnterior)`: reutiliza la lógica actual de acumulación de `calcularMonto()` (caminar el cronograma sumando cuotas vencidas impagas), pero con `cutoff` = **último día del mes anterior** al seleccionado (si el usuario eligió Julio 2026, `cutoff` = 30 de junio de 2026).
- **Excluye explícitamente la cuota del mes seleccionado** — solo cuenta lo vencido hasta el mes anterior inclusive.
- **No aplica** el "cobro igual si está al día" — si no hay nada vencido antes del mes seleccionado, el monto es 0 y el contrato se excluye del archivo (a diferencia de Activos, aquí "estar al día" significa correctamente que no aplica, no que se fuerce un cobro).
- Si el contrato inició en el mismo mes seleccionado o después, no puede tener mora anterior — se excluye automáticamente (cronograma vacío antes del corte).

**Caso de prueba concreto:** mismos filtros que HU-02 pero modo Morosos, mes Julio 2026 (`cutoff` = 30/06/2026) → deben aparecer solo los 3 contratos que ya arrastraban atraso real de meses anteriores (los mismos que el sistema generaba *antes* de la corrección de "cobro igual si está al día"), no los 6 que solo debían la cuota de julio.

---

## HU-04 — Evitar cobros duplicados y colisión de archivos entre modos

**Como** sistema
**Quiero** que el identificador único del archivo generado incluya el modo (Activos/Morosos)
**Para** que generar ambos para el mismo banco/nómina/fecha no se detecten como "ya procesado" entre sí, y para que no se pueda aplicar dos veces el mismo cobro por error

**Criterios de aceptación**
- La clave de deduplicación (`$sPar`/`$md5Nom` en `mgtxt_bolivares.php`) incluye el modo elegido (ej. agregar `' MODO:ACTIVOS'` o `' MODO:MOROSOS'` a la cadena antes del hash) — así "Activos Julio 2026" y "Morosos Julio 2026" del mismo banco/nómina son archivos independientes, cada uno con su propio control de "ya procesado".
- Generar el modo Activos y luego el modo Morosos (o viceversa) para el mismo mes/banco/nómina debe funcionar sin bloquearse mutuamente — son archivos distintos.
- Sí debe seguir bloqueando la reutilización del **mismo** modo+filtros+fecha ya procesado (comportamiento actual, sin cambios).

---

## Fuera de alcance

- No se modifica el modal "Generar Txt" en USD (`crear_txt.js`/`form_archivos.php`) — esta HU es específica del modal "TXT Bolívares". Si se requiere el mismo selector allí, es una HU aparte.
- No se cambia el formato de archivo 51/54 caracteres.
- No se implementa un tercer modo "todo combinado" — si se necesita, es una HU futura explícita.
