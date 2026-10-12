# Historias de usuario — Límite de 9999 registros por TXT en "TXT Bolívares"

Contexto técnico (para quien implemente):

- Pantalla: `Crear_Txt` (`http://localhost:8000/index.php/cooperativa/Crear_Txt`) → botón/modal **"TXT Bolívares"** (`crear_txt_bs.js` + `reportes/form_archivos_crear_bs.php`).
- Flujo: JS `crear_txt_bs()` → POST `sUrlCobranza + "GtxtBs"` → controlador `cooperativa.php` (revisar método que enruta a `Mgtxt_bolivares`) → `mgtxt_bolivares.php::GtxtBs()` (línea 20) → `GtxtGenerator::generarBolivaresConCorte()` (`gtxt_generator.php:292`).
- Formato de archivo (fijo, ver `gtxt_generator.php:903` `armarArchivo()`):
  - **Línea de cabecera** (51 caracteres): cuenta matriz (20) + fecha archivo (8) + monto total en centavos (17) + **cantidad de registros (4 dígitos, `str_pad(..., 4, '0', STR_PAD_LEFT)`)** + código de referencia (2).
  - **Líneas de detalle** (54 caracteres): una por cada línea de cobro (después de aplicar "Método de Picar").
  - **El campo de cantidad de la cabecera es de 4 dígitos → físicamente no puede representar más de 9999.** Esto ya es la razón técnica exacta detrás de la nueva restricción bancaria: nunca se pudo declarar un archivo de 10000+ líneas en la cabecera, aunque hasta ahora el sistema sí las escribía todas en el detalle sin dividir.
  - Hoy `generarBolivaresConCorte()` construye **un solo** `$registros` con todas las líneas, llama `armarArchivo()` una vez y escribe **un solo** archivo con `file_put_contents($txtPath, $contenido)` (línea ~410). `mgtxt_bolivares.php::GtxtBs()` devuelve **un solo** enlace `+ Descargar TXT`.
- Esta HU cubre el conteo de **líneas de detalle ya generadas** (es decir, después de aplicar "Método de Picar" — si un cobro se fracciona en varias líneas, cada línea cuenta para el límite de 9999).

**Objetivo de negocio (tal como lo explicó el usuario):** los bancos ahora rechazan archivos con más de 9999 líneas de registro. Si el resultado a generar supera esa cantidad, el sistema debe partirlo automáticamente en varios archivos TXT (de máximo 9999 líneas cada uno) y permitir descargar todos los archivos generados, en vez de fallar o truncar el archivo.

---

## HU-01 — Partir el resultado en bloques de máximo 9999 registros

**Como** sistema
**Quiero** dividir automáticamente las líneas de detalle en bloques de máximo 9999 registros cada uno
**Para** que ningún archivo generado viole la nueva restricción bancaria de línea de cabecera (máx. 9999)

**Criterios de aceptación**
- En `GtxtGenerator::generarBolivaresConCorte()` (`gtxt_generator.php:292`), definir una constante `LIMITE_LINEAS_POR_ARCHIVO = 9999`.
- Después de armar el arreglo completo `$registros` (todas las líneas ya calculadas, con picar aplicado), dividirlo en bloques (`array_chunk($registros, 9999)`).
- Si el total de líneas es ≤ 9999 → se genera **un solo** archivo (comportamiento actual, sin cambios visibles para el usuario).
- Si el total de líneas es > 9999 → se genera **un archivo por cada bloque de hasta 9999 líneas**. Ejemplo: 10000 registros → 2 archivos (9999 + 1); 25000 registros → 3 archivos (9999 + 9999 + 7002... ajustar ejemplo real: 9999+9999+7002=25000, correcto).
- Cada archivo se escribe con su propio nombre físico (ej. `$md5Nom . '_parte1.txt'`, `$md5Nom . '_parte2.txt'`, ...; si solo hay un archivo no se agrega sufijo, para no romper compatibilidad con archivos ya generados/descargados previamente).
- `generarBolivaresConCorte()` debe retornar una lista de archivos generados (cada uno con su propio `path`, `cant` y `mont`), no un solo `path`/`cant`/`mont` como hoy.

---

## HU-02 — Cabecera propia y correcta por cada archivo (regla de oro)

**Como** sistema
**Quiero** que cada archivo TXT generado incluya su propia línea de cabecera, con el mismo formato/estructura que usa hoy `armarArchivo()`
**Para** que cada archivo sea válido de forma independiente ante el banco (regla de oro del negocio: todos los TXT deben llevar cabecera)

**Criterios de aceptación**
- Se llama a `armarArchivo()` **una vez por cada bloque/archivo**, no una sola vez para todo el resultado.
- La cabecera de cada archivo usa los mismos campos fijos que hoy (cuenta matriz, fecha de archivo, código de referencia) — estos **no cambian entre archivos** del mismo lote.
- La **cantidad de registros** y el **monto total** de la cabecera de cada archivo corresponden **únicamente a las líneas de detalle incluidas en ese archivo** (no al total general del lote). Esto es obligatorio: el campo de cantidad es de 4 dígitos y no puede representar el total si este supera 9999.
- Ningún archivo generado puede quedar sin cabecera o con cabecera de otro bloque (ej. copiar/pegar mal el `$montoTotal` de otro chunk).
- QA: para un caso con 10000 líneas, el archivo 1 debe tener cabecera con cantidad `9999` y su monto correspondiente a esas 9999 líneas; el archivo 2 debe tener cabecera con cantidad `0001` y el monto de esa única línea. La suma de cantidades y montos de todas las cabeceras debe coincidir con el total real generado.

---

## HU-03 — Descargar todos los archivos generados desde el modal "TXT Bolívares"

**Como** usuario de cobranza
**Quiero** poder descargar todos los archivos TXT generados cuando el resultado se dividió en varias partes
**Para** enviar al banco el lote completo sin perder ninguna parte

**Criterios de aceptación**
- `mgtxt_bolivares.php::GtxtBs()` (línea 20) recorre la lista de archivos devuelta por `generarBolivaresConCorte()` y genera **un enlace de descarga por cada archivo** (`+ Descargar TXT — Parte 1 de 2`, `+ Descargar TXT — Parte 2 de 2`, etc.). Si solo hay un archivo, se muestra igual que hoy (sin "Parte X de Y", para no generar ruido en el caso normal).
- El resumen HTML de resultado (cantidad de registros, monto total, tasa aplicada) sigue mostrando el **total general del lote completo** (suma de todos los archivos), y adicionalmente indica cuántos archivos se generaron (ej. "Se generaron 2 archivos TXT (9999 y 1 registros respectivamente)").
- El registro en `cpp_banco` (control de "ya procesado", `$sPar`/`$md5Nom`) sigue funcionando igual que hoy para todo el lote (una sola fila con el total de `cant`/`mont` del lote completo) — no se requiere una fila por archivo, solo los archivos físicos y sus enlaces se multiplican.
- Si el usuario reintenta generar el mismo lote (mismo hash `$sPar` ya procesado), el mensaje de "ya fue generado" también debe listar y enlazar **todas** las partes ya generadas, no solo la primera.

---

## Fuera de alcance

- No se modifica el modal "Generar TXT" en USD (`crear_txt.js` / controlador `Gtxt()` / `MProcesar::Gtxt()`, que usa el binario/flujo distinto) — esta HU es específica del modal **"TXT Bolívares"**. Si ese otro flujo también necesita el límite de 9999 (comparte el mismo formato de cabecera de 4 dígitos vía `GtxtGenerator::generar()` y `armarArchivo()`), es una HU aparte, mismo patrón que aquí.
- No se modifica el flujo de **Banco de Venezuela** (plantilla XLS/macro, `ExportXlsVenezuela`), que no usa este formato de cabecera de 51/54 caracteres.
- No se cambia el formato de cabecera/detalle (51/54 caracteres) en sí — solo se replica por archivo cuando hace falta partir.
- No se implementa compresión/empaquetado (ej. ZIP) de las partes — se descargan como archivos `.txt` individuales, igual que hoy.
