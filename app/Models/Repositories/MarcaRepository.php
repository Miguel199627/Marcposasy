<?php
namespace App\Models\Repositories;

use App\Models\Entities\Marca;
use App\Helpers\ResponseHelper;
use Exception;

class MarcaRepository {
  private $marca;

  public function __construct() {
    $this->marca = new Marca;
  }

  public function listar() {
    try {
      return $this->marca->orderBy('descripcion')->get();
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function upsa(Marca $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->marca->id = $model->id;
      $this->marca->descripcion = $model->descripcion;

      if($model->type) {
        $this->marca->exists = true;
        $rh->setResponse(true,'Marca actualizada con exito');
      } else {
        $rh->setResponse(true,'Marca ingresada con exito');
      }

      $this->marca->save();

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function obtener($id) {
    try {
      return $this->marca->find($id);
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function eliminar(int $id) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->marca->destroy($id);
      $rh->setResponse(true,'Marca eliminada con exito');

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }
}
