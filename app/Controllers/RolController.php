<?php
namespace App\Controllers;

use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Rol;
use App\Validations\RolValidation;
use App\Models\Repositories\RolRepository;

class RolController extends Controller {
  private $rolRepo;

  public function __construct() {
    parent::__construct();
    $this->rolRepo = new RolRepository;
  }

  public function getIndex() {
    return $this->render('rol/index.twig', [
      'title' => 'Roles'
    ]);
  }

  public function postGrid() {
    $verify = $this->rolRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function postCrud() {
    if(empty($_POST['id'])) {
      $model =  new Rol;
      return $this->render('rol/nuevoRol.twig', [
        'model' => $model
      ]);
    } else {
      $model = $this->rolRepo->obtener($_POST['id']);
      $modulos = explode(",",$model->permisos);
      return $this->render('rol/editarRol.twig', [
        'model' => $model,
        'ventas' => in_array('ventas',$modulos) ? 'checked' : null,
        'facturas' => in_array('facturas',$modulos) ? 'checked' : null,
        'ventaspormes' => in_array('ventaspormes',$modulos) ? 'checked' : null,
        'productospormes' => in_array('productospormes',$modulos) ? 'checked' : null,
        'usuarios' => in_array('usuarios',$modulos) ? 'checked' : null,
        'clientes' => in_array('clientes',$modulos) ? 'checked' : null,
        'productos' => in_array('productos',$modulos) ? 'checked' : null,
        'marcas' => in_array('marcas',$modulos) ? 'checked' : null,
        'roles' => in_array('roles',$modulos) ? 'checked' : null
      ]);
    }
  }

  public function postGuardar() {
    RolValidation::validate($_POST);

    $model = new Rol;
    $model->id = $_POST['id'];
    $model->descripcion = $_POST['descripcion'];
    $model->permisos = $this->crearStringPer($_POST);
    $model->type = false;

    $rh = $this->rolRepo->upsa($model);

    print_r(
      json_encode($rh)
    );
  }

  public function postActualizar() {
    RolValidation::validate($_POST);

    $model = new Rol;
    $model->id = $_POST['id'];
    $model->descripcion = $_POST['descripcion'];
    $model->permisos = $this->crearStringPer($_POST);
    $model->type = true;

    $rh = $this->rolRepo->upsa($model);

    print_r(
      json_encode($rh)
    );
  }

  public function postEliminar() {
    print_r(
      json_encode(
        $this->rolRepo->eliminar($_POST['id'])
      )
    );
  }

  private function crearStringPer($consulta){
    $permisos = null;
    if(!empty($consulta['ventas']) && $consulta['ventas'] == 'ventas'){
      $permisos = empty($permisos) ? $permisos.'ventas' : $permisos.',ventas';
    }
    if(!empty($consulta['facturas']) && $consulta['facturas'] == 'facturas'){
      $permisos = empty($permisos) ? $permisos.'facturas' : $permisos.',facturas';
    }
    if(!empty($consulta['ventaspormes']) && $consulta['ventaspormes'] == 'ventaspormes'){
      $permisos = empty($permisos) ? $permisos.'ventaspormes' : $permisos.',ventaspormes';
    }
    if(!empty($consulta['productospormes']) && $consulta['productospormes'] == 'productospormes'){
      $permisos = empty($permisos) ? $permisos.'productospormes' : $permisos.',productospormes';
    }
    if(!empty($consulta['usuarios']) && $consulta['usuarios'] == 'usuarios'){
      $permisos = empty($permisos) ? $permisos.'usuarios' : $permisos.',usuarios';
    }
    if(!empty($consulta['clientes']) && $consulta['clientes'] == 'clientes'){
      $permisos = empty($permisos) ? $permisos.'clientes' : $permisos.',clientes';
    }
    if(!empty($consulta['productos']) && $consulta['productos'] == 'productos'){
      $permisos = empty($permisos) ? $permisos.'productos' : $permisos.',productos';
    }
    if(!empty($consulta['marcas']) && $consulta['marcas'] == 'marcas'){
      $permisos = empty($permisos) ? $permisos.'marcas' : $permisos.',marcas';
    }
    if(!empty($consulta['roles']) && $consulta['roles'] == 'roles'){
      $permisos = empty($permisos) ? $permisos.'roles' : $permisos.',roles';
    }
    return $permisos;
  }
}
?>
