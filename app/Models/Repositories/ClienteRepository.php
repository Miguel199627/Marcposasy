<?php
namespace App\Models\Repositories;

use App\Models\Entities\Cliente;
use App\Helpers\ResponseHelper;
use Exception;

class ClienteRepository {
  private $cliente;

  public function __construct() {
    $this->cliente = new Cliente;
  }

  public function upsa(Cliente $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->cliente->id = $model->id;
      $this->cliente->nombres = $model->nombres;
      $this->cliente->apellidos = $model->apellidos;
      $this->cliente->direccion = $model->direccion;
      $this->cliente->telefono = $model->telefono;
      $this->cliente->email = $model->email;

      if($model->type) {
        $this->cliente->exists = true;
        $rh->setResponse(true,'Cliente actualizado con exito');
      } else {
        $rh->setResponse(true,'Cliente ingresado con exito');
      }

      $this->cliente->save();

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function obtener($id) {
    try {
      return $this->cliente->find($id);
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function listar() {
    try {
      return $this->cliente->orderBy('id','ASC')->get();
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function eliminar(int $id) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->cliente->destroy($id);
      $rh->setResponse(true,'Cliente eliminado con exito');

    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }
}
?>
