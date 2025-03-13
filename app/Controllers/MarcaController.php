<?php
namespace App\Controllers;

use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Marca;
use App\Validations\MarcaValidation;
use App\Models\Repositories\MarcaRepository;

class MarcaController extends Controller {
  private $marcaRepo;

  public function __construct() {
    parent::__construct();
    $this->marcaRepo = new MarcaRepository;
  }

  public function getIndex() {
    return $this->render('marca/index.twig', [
      'title' => 'Marcas'
    ]);
  }

  public function postGrid() {
    $verify = $this->marcaRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function postCrud() {
    if(empty($_POST['id'])) {
      $model =  new Marca;
      $ruta = 'marca/nuevaMarca.twig';
    } else {
      $model = $this->marcaRepo->obtener($_POST['id']);
      $ruta = 'marca/editarMarca.twig';
    }
    return $this->render($ruta, [
      'title' => 'Marca',
      'model' => $model
    ]);
  }

  public function postGuardar() {
    MarcaValidation::validate($_POST);

    $model = new Marca;
    $model->id = $_POST['id'];
    $model->descripcion = $_POST['descripcion'];
    $model->type = false;

    $rh = $this->marcaRepo->upsa($model);

    print_r(
      json_encode($rh)
    );
  }

  public function postActualizar() {
    MarcaValidation::validate($_POST);

    $model = new Marca;
    $model->id = $_POST['id'];
    $model->descripcion = $_POST['descripcion'];
    $model->type = true;

    $rh = $this->marcaRepo->upsa($model);

    print_r(
      json_encode($rh)
    );
  }

  public function postEliminar() {
    print_r(
      json_encode(
        $this->marcaRepo->eliminar($_POST['id'])
      )
    );
  }
}
?>
