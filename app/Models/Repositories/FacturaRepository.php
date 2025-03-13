<?php
namespace App\Models\Repositories;

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Entities\Factura;
use App\Helpers\ResponseHelper;
use Exception;

class FacturaRepository {
  private $factura;

  public function __construct() {
    $this->factura = new Factura;
  }

  public function guardar(Factura $model, array $detalle) {
    try {
      DB::beginTransaction();

      $model->save();

      $model->detalle()->saveMany($detalle);

      $rh = new \stdClass;
      $rh->response = true;
      $rh->id = $model->id;

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      $rh = new ResponseHelper;
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function obtener($id) {
    try {
      return $this->factura->find($id);
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function listar() {
    try {
      return $this->factura->select('factura.id',DB::raw("CONCAT(cliente.nombres,' ',cliente.apellidos) AS nombre_cliente"),DB::raw("CONCAT(usuario.nombres,' ',usuario.apellidos) AS nombre_usuario"),'iva','total','neto','factura.estado','factura.created_at')
      ->leftJoin('cliente', 'factura.cliente_id', '=', 'cliente.id')
      ->leftJoin('usuario', 'factura.usuario_id', '=', 'usuario.id')
      ->orderBy('factura.id','DESC')
      ->get();
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function anular(int $id, $type) : ResponseHelper {
    $rh = new ResponseHelper;

    try {
      $this->factura->id = $id;
      $this->factura->estado = $type ? 0 : 1;
      $this->factura->exists = true;

      $this->factura->save();
      $rh->setResponse(true,'Factura anulada con exito');
    } catch (Exception $e) {
      return $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function eliminar(int $id) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->factura->destroy($id);
      $rh->setResponse(true);

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }
}
?>
