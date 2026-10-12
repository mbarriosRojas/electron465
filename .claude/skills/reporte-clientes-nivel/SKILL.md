---
name: reporte-clientes-nivel
description: Genera el Excel de clientes que ya cancelaron todo su crédito (saldo 0) y no tienen contratos activos, con cédula, nombre, nómina y banco, clasificados ORO / PLATA / BRONCE según cómo pagaron. Usar cuando el usuario pida el reporte de clientes cancelados, clientes oro/plata/bronce, buenos pagadores o similar.
---

# Reporte clientes cancelados (Oro / Plata / Bronce)

El usuario NO quiere este reporte como código dentro del sistema: solo quiere el archivo cada vez que lo pide.

1. Ejecutar:
   ```
   python3 /Volumes/goku/dev_php/.claude/skills/reporte-clientes-nivel/generar_reporte.py
   ```
   - Base por defecto: `copy_electron` (copia local de producción, root sin clave). Usar `--db <base>` si piden otra.
   - Salida por defecto: `/Volumes/goku/dev_php/db_backups/reporte_clientes_cancelados_nivel_<fecha_hora>.xlsx` (`--out DIR` para cambiarla).
   - Nunca consultar producción (`grupoele_electron`) sin que el usuario lo pida.
2. Responder en español con el link al archivo, la tabla de conteos por nivel y la fecha de "datos hasta" que imprime el script (si la copia local es vieja, avisarlo).

Si pide cambiar criterios, editar las constantes `ORO`, `BRONCE`, `AL_DIA` o `TOL` al inicio del script. Los criterios están explicados en la hoja "Resumen" del Excel.
