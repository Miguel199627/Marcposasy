CREATE VIEW reporte_producto
AS
SELECT
  f.id,
  YEAR(f.created_at) anio,
  MONTH(f.created_at) mes,
  cd.cantidad,
  p.descripcion producto,
  (p.costo*cd.cantidad) costo,
  cd.total,
  (cd.total - (p.costo*cd.cantidad)) utilidad
FROM factura f
INNER JOIN movimientofactura cd
ON cd.factura_id = f.id
INNER JOIN producto p
ON p.id = cd.producto_id
WHERE f.estado = 1
