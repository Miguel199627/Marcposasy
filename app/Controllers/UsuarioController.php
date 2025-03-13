<?php
namespace App\Controllers;

use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Usuario;
use App\Validations\UsuarioValidation;
use App\Models\Repositories\{UsuarioRepository,RolRepository};

class UsuarioController extends Controller {
  private $usuarioRepo;
  private $rolRepo;

  public function __construct() {
    parent::__construct();
    $this->usuarioRepo = new UsuarioRepository;
    $this->rolRepo = new RolRepository;
  }

  public function getIndex() {
    return $this->render('usuario/index.twig', [
      'title' => 'Usuarios'
    ]);
  }

  public function postGrid() {
    $verify = $this->usuarioRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function postCrud() {
    if(empty($_POST['id'])) {
      $model =  new Usuario;
      $ruta = 'usuario/nuevoUsuario.twig';
    } else {
      $model = $this->usuarioRepo->obtener('usuario.id',$_POST['id']);
      $ruta = 'usuario/editarUsuario.twig';
    }
    return $this->render($ruta, [
      'title' => 'Usuario',
      'model' => $model,
      'rol'   => $this->rolRepo->listar()
    ]);
  }

  public function postGuardar() {
    $rh = new ResponseHelper;

    UsuarioValidation::validate($_POST);
    $verify = $this->usuarioRepo->obtener('email',strtolower($_POST['email']));

    if(empty($verify)) {

      $model = new Usuario;
      $model->id = $_POST['id'];
      $model->nombres = $_POST['nombres'];
      $model->apellidos = $_POST['apellidos'];
      $model->email = strtolower($_POST['email']);
      $model->estado = empty($_POST['estado']) ? 0 : 1;
      $model->contraseña = $_POST['contraseña'];
      $model->rol_id = $_POST['rol_id'];
      $model->type = false;

      $rh = $this->usuarioRepo->upsa($model);

    } else if(!empty($verify->response)) {
      $rh->setResponse(false,$verify->message);
    } else if(!empty($verify) && empty($verify->response)) {
      $rh->setResponse(false,'El correo ingresado ya existe');
    }

    print_r(
      json_encode($rh)
    );
  }

  public function postActualizar() {
    UsuarioValidation::validate($_POST);

    $model = new Usuario;
    $model->id = $_POST['id'];
    $model->nombres = $_POST['nombres'];
    $model->apellidos = $_POST['apellidos'];
    $model->email = strtolower($_POST['email']);

    $elementos = $this->usuarioRepo->obtener('usuario.id',$model->id);
    if($model->email != $elementos['email'] && !empty($this->usuarioRepo->obtener('email',$model->email))) {
      $rh = new ResponseHelper();
      $rh->setResponse(false, 'El correo ingresado ya esta ocupado');
      exit(json_encode($rh));
    }

    $model->estado = empty($_POST['estado']) ? 0 : 1;
    $model->contraseña = $_POST['contraseña'];
    $model->rol_id = $_POST['rol_id'];
    $model->type = true;

    $rh = $this->usuarioRepo->upsa($model);

    print_r(
      json_encode($rh)
    );
  }

  public function postEliminar() {
    print_r(
      json_encode(
        $this->usuarioRepo->eliminar($_POST['id'])
      )
    );
  }
}
?>
