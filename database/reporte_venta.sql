CREATE view reporte_venta
AS
SELECT
  YEAR(f.created_at) anio,
  MONTH(f.created_at) mes,
  SUM(cd.costo) costo,
  SUM(f.total) total,
  SUM(f.total - cd.costo) utilidad
FROM factura f
INNER JOIN (
  SELECT factura_id, SUM(producto.costo * cantidad) costo FROM movimientofactura
  INNER JOIN producto ON movimientofactura.producto_id = producto.id
  GROUP BY factura_id
) cd ON cd.factura_id = f.id
WHERE f.estado = 1
GROUP BY anio, mes
ORDER BY anio desc, mes desc
