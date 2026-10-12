# Historias de usuario — Generar TXT de cobranza por banco (BANFAN, BICENTENARIO) con Dólar Cobranza

Contexto técnico (para quien implemente):

- El generador de archivos vive en `system/application/models/cobranza/gtxt_generator.php` (clase `GtxtGenerator`), con dos flujos hoy independientes:
  - `generar($param)` — flujo **USD, con corte "Hasta la Fecha"**: usa `gPlanCobro()` para construir el calendario teórico de cuotas y `calcularMonto()` para acumular **todas** las cuotas vencidas hasta la fecha de corte, restando lo ya pagado (`t_lista_cobros`). Escribe el monto en USD directo al archivo (sin convertir a Bs). Lo usa el modal actual "Generar Txt" (tipo A/C/M).
  - `generarBolivares($param)` — flujo **Bs, sin corte de fecha**: solo calcula `min(resta, monto_cuota)` (como mucho una cuota) y lo multiplica por la tasa del dólar (hoy BCV, vía `MPrecio_dolar::getPrecioActivo()`/`getUltimoRegistroVigente()`, sin parámetro de tipo). Lo usa el modal "TXT Bolívares" (`GtxtBs`, ya funcionando en producción).
- **El formato de archivo (51 bytes encabezado / 54 bytes detalle) ya está implementado en `armarArchivo()` y es idéntico para BANFAN y BICENTENARIO** — confirmado comparando el código contra `docs/fanban.doc` y la lámina 8 de `docs/bicentenario.PPTX`, campo por campo. **No hace falta escribir un parser/writer nuevo.**
- `system/application/config/gtxt_config.php` tiene cuenta matriz y código de empresa para BICENTENARIO (y otros 9 bancos), pero **no tiene entrada para BANFAN** — sin eso, `resolverCuentaMatriz('BANFAN', ...)` devuelve vacío y la generación falla con "No hay cuenta matriz configurada".
- `resolverCuentaMatriz()`/`resolverCodigoEmpresa()` ya soportan overrides por crédito/empresa vía `t_afiliar_txt`, además del default de `gtxt_config.php` — no se toca esa parte.
- El cálculo `monto_bs = round(min(resta, monto_cuota) * tasa, 2)` (fórmula ya usada en Bolívares y en Venezuela) es la base a **reutilizar**, pero ahora acumulando sobre **todas** las cuotas vencidas hasta la fecha de corte (igual que ya hace `calcularMonto()` para USD), no solo una.
- El "Dólar Cobranza" ya existe como tipo de tasa independiente (`t_precio_dolar.tipo='COBRANZA'`, ver `docs/HUs-panel-dolar-dual-y-tasa-por-fecha-cobro.md`), registrado obligatoriamente junto al BCV en `/cooperativa/panelDolar`, pero **hoy ningún generador de TXT lo consume todavía** — todos siguen apuntando a BCV.
- UI actual: el modal "Generar Txt" (`crear_txt.js` + `reportes/form_archivos.php`) ya tiene selector de banco (linaje), empresa, periodicidad, forma de contrato, nómina y fecha "Hasta la Fecha" — pero envía el archivo en USD sin convertir. El modal "TXT Bolívares" (`crear_txt_bs.js`) convierte a Bs pero no tiene fecha de corte ni selector de banco tan amplio.

**Decisiones confirmadas con el usuario:**
- BANFAN sí se configura (cuenta matriz pendiente de que el usuario la aporte — ver HU-01).
- El corte "Hasta la Fecha" debe acumular **todas** las cuotas vencidas hasta esa fecha (no solo el saldo/una cuota), igual que ya hace el flujo USD existente.

**Estado de implementación (08/07/2026):** HU-02, HU-03 y HU-04 implementadas y verificadas contra datos reales de BICENTENARIO (acumulación de cuotas + conversión con Dólar Cobranza calculada a mano y confirmada exacta contra 2 clientes reales con múltiples créditos vencidos). HU-01 queda con la estructura de configuración lista (`gtxt_config.php` con `BANFAN` en `gtxt_formato_bancario`) pero con cuenta matriz vacía a propósito — la generación falla con mensaje claro hasta que se ingresen los números reales.

---

## HU-01 — Configurar BANFAN como banco habilitado para generación de TXT

**Como** equipo técnico
**Quiero** agregar BANFAN a la configuración de bancos (`gtxt_config.php`)
**Para** que la generación de su archivo de cobranza no falle por falta de cuenta matriz

**Criterios de aceptación**
- `gtxt_cuenta_matriz['BANFAN']` con las cuentas matriz para empresa `0` (Cooperativa) y `1` (Grupo), aportadas por el usuario/negocio.
- `BANFAN` agregado a `gtxt_formato_bancario` (ya usa el layout 51/54, no requiere código nuevo).
- Si no hay código de empresa específico para BANFAN vía `t_afiliar_txt`, se usa el default de `gtxt_codigo_empresa_default` (mismo comportamiento que los demás bancos sin override).
- Mensaje de error claro y específico si a futuro se agrega otro banco sin cuenta matriz configurada (ya existe: "No hay cuenta matriz configurada para BANCO / empresa X" — verificar que llegue visible al usuario en el modal, no solo en el log).

**Bloqueante:** requiere los números reales de cuenta matriz de BANFAN (Cooperativa y Grupo) antes de poder cerrarse.

---

## HU-02 — Generar TXT de cobranza en Bs acumulando TODAS las cuotas vencidas "Hasta la Fecha"

**Como** usuario de cobranza
**Quiero** que al generar el TXT de un banco (BANFAN, BICENTENARIO, u otro) indicando una fecha de corte "Hasta la Fecha", el sistema cobre **todas** las cuotas vencidas y no pagadas hasta esa fecha (no solo una)
**Para** recuperar la mora completa de un cliente atrasado, no solo su cuota más reciente

**Contexto:** hoy existen dos cálculos distintos y ninguno hace exactamente esto:
- El flujo USD (`generar()`/`calcularMonto()`) sí acumula todas las cuotas vencidas hasta la fecha de corte — pero escribe el monto en USD, sin convertir a Bs.
- El flujo Bolívares (`generarBolivares()`) convierte a Bs — pero no tiene fecha de corte y solo cobra como mucho una cuota (`min(resta, monto_cuota)`).

**Criterios de aceptación**
- Nueva variante de cálculo que reutiliza la lógica de `calcularMonto()` (plan de cuotas vía `gPlanCobro()`, acumulando el adeudado de cada cuota vencida hasta el corte, restando lo ya pagado en `t_lista_cobros`) para obtener el monto adeudado **en USD**.
- Ese monto en USD se multiplica por la tasa vigente para obtener el monto en Bs a escribir en el archivo (ver HU-03 para qué tasa exactamente).
- Si el cliente no tiene cuotas vencidas hasta la fecha de corte (`adeudado <= 0`), no se genera línea de detalle para ese contrato (mismo criterio que ya aplican ambos flujos existentes).
- El archivo resultante sigue el mismo formato fijo 51/54 caracteres ya implementado en `armarArchivo()` — sin cambios en el escritor de archivo.
- `picar` (fraccionamiento del monto en partes, ej. 302 Bs en partes de 100) sigue aplicando igual que hoy, ahora sobre el monto en Bs ya acumulado y convertido.

---

## HU-03 — Usar el Dólar Cobranza (no BCV) para convertir USD → Bs en la generación de TXT bancario

**Como** usuario de cobranza
**Quiero** que la conversión de USD a Bs en la generación de estos archivos use la tasa **Dólar Cobranza** del día, no la tasa BCV
**Para** que el monto cobrado al cliente refleje la tasa que la cooperativa define específicamente para cobranza (que ya se registra junto al BCV en el Panel del Dólar)

**Criterios de aceptación**
- La búsqueda de tasa (hoy `MPrecio_dolar::getPrecioActivo()` / `getUltimoRegistroVigente()` sin tipo, en `mgtxt_bolivares.php`) pasa a pedir explícitamente `tipo = 'COBRANZA'`.
- Si no hay Dólar Cobranza registrado para el día (ni un último valor vigente), la generación se bloquea con un mensaje claro: *"No hay tasa de Dólar Cobranza registrada. Regístrela en Panel → Precio del Dólar."* — mismo patrón que ya existe hoy para la falta de tasa BCV, no se genera nada.
- El mensaje de éxito y la leyenda de vista previa del modal muestran explícitamente qué tasa se usó y de qué fecha (ej. "Tasa Dólar Cobranza: 735,00 Bs./USD (08/07/2026)"), para que quede claro que ya no es BCV.
- El endpoint de previsualización de tasa en el modal (`getPrecioDolarVigente`) se consulta pidiendo el tipo Cobranza para este flujo específico (ya soporta el parámetro `tipo`, agregado junto con el Dólar Cobranza).

**Fuera de alcance:** esta HU solo cambia la fuente de tasa para la generación de TXT bancario (BANFAN/BICENTENARIO y demás bancos del formato 51/54 + Bolívares genérico). No toca la generación de Banco Venezuela (`gtxt_venezuela_generator.php`), que sigue usando BCV salvo que se pida explícitamente en otra HU.

---

## HU-04 — Selector de banco y fecha de corte en el modal de generación de TXT Bolívares

**Como** usuario de cobranza
**Quiero** poder elegir el banco (incluyendo BANFAN) e indicar la fecha "Hasta la Fecha" al generar el TXT en Bolívares
**Para** controlar exactamente qué se va a cobrar y a partir de cuándo

**Criterios de aceptación**
- El modal "TXT Bolívares" agrega el campo de fecha de corte "Hasta la Fecha" (hoy ausente en ese modal, solo existe en el modal USD).
- El combo de banco ya lista los linajes asignados al usuario (incluye BANFAN una vez configurado en HU-01) — no requiere cambios en el combo, solo verificar que BANFAN aparezca tras la HU-01.
- El texto de ayuda estático que lista "otros bancos soportados" en la vista se actualiza para incluir BANFAN.
- Al generar, se aplican en conjunto HU-02 (acumulación de cuotas vencidas) y HU-03 (tasa Dólar Cobranza).

---

## Fuera de alcance (todas las HU de este documento)

- No se modifica `gPlanCobro()` (el cálculo aproximado del calendario de cuotas) — se reutiliza tal cual, con sus limitaciones conocidas (aproximación de meses, no calendario exacto).
- No se modifica el flujo de Banco Venezuela (`gtxt_venezuela_generator.php`), que mantiene su propio formato de archivo y su propia fuente de tasa (BCV) salvo decisión explícita futura.
- No se automatiza la obtención de la cuenta matriz de BANFAN — debe aportarla el negocio (HU-01).
- No se implementa el cobro de comisión del banco (0.60% + 0.30% mencionado en `bicentenario.PPTX`) — es informativo, no se factura desde el sistema.
