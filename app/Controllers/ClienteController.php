<?php
namespace App\Controllers;

use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Cliente;
use App\Validations\ClienteValidation;
use App\Models\Repositories\ClienteRepository;

class ClienteController extends Controller {
  private $clienteRepo;

  public function __construct() {
    parent::__construct();
    $this->clienteRepo = new ClienteRepository;
  }

  public function getIndex() {
    return $this->render('cliente/index.twig', [
      'title' => 'Clientes'
    ]);
  }

  public function postGrid() {
    $verify = $this->clienteRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function postCrud() {
    if(empty($_POST['id'])) {
      $model =  new Cliente;
      $ruta = 'cliente/nuevoCliente.twig';
    } else {
      $model = $this->clienteRepo->obtener($_POST['id']);
      $ruta = 'cliente/editarCliente.twig';
    }
    return $this->render($ruta, [
      'title' => 'Cliente',
      'model' => $model
    ]);
  }

  public function postConsultar() {
    $rh = $this->clienteRepo->obtener($_POST['id']);
    if(!empty($rh->response)) {
      $mensaje = $rh->message;
      $rh = new ResponseHelper;
      $rh->setResponse(false,$mensaje);
    }
    print_r(
      json_encode($rh)
    );
  }

  public function postGuardar() {
    $rh = new ResponseHelper;

    ClienteValidation::validate($_POST);
    $verify = $this->clienteRepo->obtener($_POST['id']);

    if(empty($verify)) {

      $model = new Cliente;
      $model->id = $_POST['id'];
      $model->nombres = $_POST['nombres'];
      $model->apellidos = $_POST['apellidos'];
      $model->direccion = $_POST['direccion'];
      $model->telefono = $_POST['telefono'];
      $model->email = $_POST['email'];
      $model->type = false;

      $rh = $this->clienteRepo->upsa($model);
    } else if (!empty($verify->response)) {
      $rh->setResponse(false,$verify->message);
    } else if (!empty($verify) && empty($verify->response)) {
      $rh->setResponse(false,'El cliente ingresado ya existe');
    }

    print_r(
      json_encode($rh)
    );
  }

  public function postActualizar() {
    $rh = new ResponseHelper;

    ClienteValidation::validate($_POST);
    $verify = $this->clienteRepo->obtener($_POST['id']);

    if(empty($verify)) {
      $rh->setResponse(false,'El cliente actual no existe');
    } else if(!empty($verify->response)) {
      $rh->setResponse(false,$verify->message);
    } else if(!empty($verify) && empty($verify->response)) {
      $model = new Cliente;
      $model->id = $_POST['id'];
      $model->nombres = $_POST['nombres'];
      $model->apellidos = $_POST['apellidos'];
      $model->direccion = $_POST['direccion'];
      $model->telefono = $_POST['telefono'];
      $model->email = $_POST['email'];
      $model->type = true;

      $rh = $this->clienteRepo->upsa($model);
    }

    print_r(
      json_encode($rh)
    );
  }

  public function postEliminar() {
    print_r(
      json_encode(
        $this->clienteRepo->eliminar($_POST['id'])
      )
    );
  }
}
?>
