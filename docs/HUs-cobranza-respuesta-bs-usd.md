# Historias de usuario — Respuesta banco (Bs) → Cobro en USD

## HU-01 — Registrar tasa al cargar respuesta Venezuela
**Como** usuario de cobranza  
**Quiero** que al subir el CSV del Banco Venezuela el sistema tome la tasa BCV de la **fecha de envío**  
**Para** vincular el lote de respuesta con la misma tasa usada al generar la domiciliación  

**Criterios de aceptación**
- Al elegir fecha enviado se muestra la tasa disponible (preview).
- Si no hay tasa para esa fecha, no permite subir y muestra mensaje claro.
- Se guarda `precio_dolar` y `fecha_tasa` en `t_archivos_venezuela`.

---

## HU-02 — Leer CSV del banco (montos en Bs)
**Como** usuario de cobranza  
**Quiero** importar las líneas RG010/RG012 del CSV  
**Para** ver cada cobro del banco en bolívares antes de aplicarlo  

**Criterios de aceptación**
- Monto del banco se guarda en `monto_bs` (y `monto` legacy).
- No modifica saldo del contrato hasta guardar lote.

---

## HU-03 — Visualizar conversión Bs → USD en pendientes
**Como** usuario de cobranza  
**Quiero** ver en la grilla Monto Bs., Monto USD y la tasa del lote  
**Para** validar antes de contabilizar  

**Criterios de aceptación**
- Columnas: Monto Bs., Monto USD (Bs / tasa).
- Leyenda con tasa, totales Bs. y USD.
- Sin tasa: no habilita Guardar lote.

---

## HU-04 — Guardar lote en USD
**Como** usuario de cobranza  
**Quiero** que al guardar el lote el pago se registre en **dólares** en `t_lista_cobros`  
**Para** que cuadre con contratos en USD (`monto_total`, `resta`, morosidad)  

**Criterios de aceptación**
- `monto_usd = monto_bs / tasa_del_archivo` (4 decimales).
- Descripción incluye Bs y tasa para auditoría.
- Archivo pasa a `estatus = 1`.
- Todas las filas deben tener contrato asignado.

---

## HU-05 — Endpoints en controller `cobranza` (sin tocar cooperativa.php)
**Como** equipo técnico  
**Quiero** APIs de Venezuela en `cobranza.php` con rutas alias  
**Para** desplegar por FileZilla sin reemplazar `cooperativa.php`  

**Endpoints:** `subirVenezuela`, `leerArchivoVenezuela`, `listarArchivoVenezuela`, `Guardar_Lote_Venezuela`, `getTasaPorFecha`, etc.

---

## HU-06 — Consulta histórica de tasa (Precio del Dólar)
**Como** sistema  
**Quiero** `MPrecio_dolar::getPrecioPorFecha(fecha)`  
**Para** resolver la tasa de cualquier día de envío desde `t_precio_dolar` o bitácora  

**Fuera de alcance v1:** carga automática de respuesta TXT Bolívares (Bicentenario); enlace directo lote salida `cpp_banco` tarc=B.
