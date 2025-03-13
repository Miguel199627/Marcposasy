<?php
namespace App\Models\Repositories;

use App\Models\Entities\{Cliente, Producto, Factura, Usuario, Rol, Marca};
use App\Models\Entities\SqlViews\{ReporteVenta,ReporteProducto};
use App\Helpers\ResponseHelper;
use Exception;

class LandingRepository {
  private $cliente;
  private $producto;
  private $factura;
  private $reporte_venta;
  private $reporte_producto;
  private $usuario;
  private $rol;
  private $marca;

  public function __construct() {
      $this->cliente = new Cliente;
      $this->producto = new Producto;
      $this->factura = new Factura;
      $this->reporte_venta = new ReporteVenta;
      $this->reporte_producto = new ReporteProducto;
      $this->usuario = new Usuario;
      $this->rol = new Rol;
      $this->marca = new Marca;
  }

  public function obtener() : \stdClass {
    try {
      $model = [];

      $model['productos'] = $this->producto->count();
      $model['usuarios'] = $this->usuario->count();
      $model['roles'] = $this->rol->count();
      $model['marcas'] = $this->marca->count();
      $model['clientes'] = $this->cliente->count();

      $model['comprobantes'] = $this->factura->where('estado', 1)
                                              ->count();

      $model['utilidad_mensual'] = $this->reporte_venta->where('anio', date('Y'))
                                                        ->where('mes', date('m'))
                                                        ->sum('utilidad');

      $model['utilidad_total'] = $this->reporte_venta->sum('utilidad');
      $model['productos_mensual'] = $this->reporte_producto->where('anio', date('Y'))
                                                            ->where('mes', date('m'))
                                                            ->sum('cantidad');
      $model['productos_totales'] = $this->reporte_producto->sum('cantidad');
    } catch (Exception $e) {
      $model = [];
      $model['error'] = $e->getMessage();
      $model['alert'] = 'alert';
      $model['alert_type'] = 'alert-warning';
    }
    return (object)$model;
  }
}
