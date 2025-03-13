<?php
namespace App\Models\Repositories;

use App\Models\Entities\Rol;
use App\Helpers\ResponseHelper;
use Exception;

class RolRepository {
  private $rol;

  public function __construct() {
    $this->rol = new Rol;
  }

  public function listar() {
    try {
      return $this->rol->orderBy('descripcion')->get();
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function upsa(Rol $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->rol->id = $model->id;
      $this->rol->descripcion = $model->descripcion;
      $this->rol->permisos = $model->permisos;

      if($model->type) {
        $this->rol->exists = true;
        $rh->setResponse(true,'Rol actualizado con exito');
      } else {
        $rh->setResponse(true,'Rol ingresado con exito');
      }

      $this->rol->save();

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function obtener($id) {
    try {
      return $this->rol->find($id);
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function eliminar(int $id) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->rol->destroy($id);
      $rh->setResponse(true,'Rol eliminado con exito');

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }
}
