<?php
namespace App\Models\Repositories;

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Entities\Producto;
use App\Helpers\ResponseHelper;
use Exception;

class ProductoRepository {
  private $producto;

  public function __construct() {
    $this->producto = new Producto;
  }

  public function upsa(Producto $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->producto->id = $model->id;
      if(!empty($model->foto)) {
        $this->producto->foto = $model->foto;
      }
      $this->producto->descripcion = $model->descripcion;
      $this->producto->marca_id = $model->marca_id;
      $this->producto->costo = $model->costo;
      $this->producto->precio = $model->precio;

      if($model->type) {
        $this->producto->exists = true;
        $rh->setResponse(true,'Producto actualizado con exito');
      } else {
        if(!empty($model->entradas)) {
          $this->producto->entradas = $model->entradas;
        }
        if(!empty($model->stock)) {
          $this->producto->stock = $model->stock;
          $this->producto->salidas = 0;
        }
        $rh->setResponse(true,'Producto ingresado con exito');
      }

      $this->producto->save();

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function savexist(Producto $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->producto->id = $model->id;
      $this->producto->entradas = $model->entradas;
      $this->producto->stock = $model->stock;
      $this->producto->salidas = $model->salidas;

      $this->producto->exists = true;

      $this->producto->save();

      $rh->setResponse(true,'Existencias agregadas con exito');
    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function updatexist($detalle, $type) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $data = [];
      foreach ($detalle as $value) {
        $resul = $this->producto->find($value->producto_id);
        $model = new Producto;
        $model->id = $resul['id'];
        $model->stock = !$type ? $resul['stock'] - $value->cantidad : $resul['stock'] + $value->cantidad;
        $model->salidas = !$type ? $resul['salidas'] + $value->cantidad : $resul['salidas'] - $value->cantidad;
        $model->exists = true;
        $data[] = $model;
      }
      DB::beginTransaction();
      foreach ($data as $d) {
        $d->save();
      }
      $rh->setResponse(true);
      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function obtener($id) {
    try {
      return $this->producto->find($id);
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function listar() {
    try {
      $result = $this->producto->orderBy('stock','ASC')->get();
      foreach ($result as $m) {
          $m->marca = $m->marca;
      }
      return $result;
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function eliminar(int $id) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $resul = $this->obtener($id);
      $this->producto->destroy($id);
      if(!empty($resul['foto'])) {
          unlink("./uploads/productos/".$resul['foto']);
      }
      $rh->setResponse(true,'Producto eliminado con exito');
    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }
}
?>
