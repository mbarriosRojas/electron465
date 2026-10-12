WITH base AS (
  SELECT lc.*,
    CASE WHEN lc.mes LIKE 'RESPUESTA %' THEN 'RESPUESTA' ELSE 'VENEZUELA' END AS origen,
    CASE WHEN lc.mes LIKE 'RESPUESTA %'
         THEN SUBSTRING_INDEX(lc.descripcion, ' | ', 1)
         ELSE SUBSTRING_INDEX(lc.descripcion, ' | Repartido', 1) END AS desc_base
  FROM t_lista_cobros lc
  WHERE (lc.mes LIKE 'RESPUESTA %' AND lc.descripcion LIKE '% BS REF %')
     OR (lc.mes LIKE 'CARGA VENEZUELA:%' AND lc.descripcion LIKE 'COBRO Bs % @ % USD%')
),
parsed AS (
  SELECT b.*,
    CASE WHEN origen='RESPUESTA'
      THEN CAST(REPLACE(REPLACE(SUBSTRING_INDEX(desc_base,' BS REF ',1),'.',''),',','.') AS DECIMAL(15,2))
      ELSE CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(desc_base,'COBRO Bs ',-1),' @',1) AS DECIMAL(15,2)) END AS bs,
    CASE WHEN origen='RESPUESTA'
      THEN CAST(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(desc_base,' BS REF ',-1),' ',1),'.',''),',','.') AS DECIMAL(15,4))
      ELSE CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(desc_base,' @ ',-1),' USD',1) AS DECIMAL(15,4)) END AS tasa
  FROM base b
)
SELECT origen, documento_id, farc, modificado, desc_base,
  COUNT(*) filas, GROUP_CONCAT(DISTINCT credito_id ORDER BY credito_id) contratos,
  MAX(bs) bs, MAX(tasa) tasa,
  ROUND(MAX(bs)/MAX(tasa),2) esperado_usd, ROUND(SUM(monto),2) guardado_usd,
  ROUND(MAX(bs)/MAX(tasa) - SUM(monto),2) faltante_usd
FROM parsed
GROUP BY origen, documento_id, farc, modificado, desc_base
HAVING ABS(faltante_usd) >= 0.02
ORDER BY faltante_usd DESC;
