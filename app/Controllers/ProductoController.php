<?php
namespace App\Controllers;

use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Producto;
use App\Validations\{ProductoValidation,ProductoValidationExist};
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Repositories\{ProductoRepository,MarcaRepository};

class ProductoController extends Controller {
  private $productoRepo;
  private $marcaRepo;

  public function __construct() {
    parent::__construct();
    $this->productoRepo = new ProductoRepository;
    $this->marcaRepo = new MarcaRepository;
  }

  public function getIndex() {
    return $this->render('producto/index.twig', [
      'title' => 'Productos'
    ]);
  }

  public function postGrid() {
    $verify = $this->productoRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function postCrud() {
    if(empty($_POST['id'])) {
      $model =  new Producto;
      $ruta = 'producto/nuevoProducto.twig';
    } else {
      $model = $this->productoRepo->obtener($_POST['id']);
      $ruta = 'producto/editarProducto.twig';
    }
    return $this->render($ruta, [
      'title' => 'Producto',
      'model' => $model,
      'marcas' => $this->marcaRepo->listar()
    ]);
  }

  public function postConsultar() {
    $rh = $this->productoRepo->obtener($_POST['id']);
    if(!empty($rh->response)) {
      $mensaje = $rh->message;
      $rh = new ResponseHelper;
      $rh->setResponse(false,$mensaje);
    }
    print_r(
      json_encode($rh)
    );
  }

  public function postExist() {
    $model = $this->productoRepo->obtener($_POST['id']);
    return $this->render('producto/agregarExistencias.twig', [
      'model' => $model,
    ]);
  }

  public function postGuardar() {
    $verificacion = true;

    ProductoValidation::validate($_POST,$_FILES);

    $model = new Producto;
    $model->id = $_POST['id'];
    $model->descripcion = $_POST['descripcion'];
    $model->marca_id = $_POST['marca_id'];
    $model->entradas = $_POST['entradas'];
    $model->stock = $_POST['entradas'];
    $model->costo = $_POST['costo'];
    $model->precio = $_POST['precio'];
    $model->type = false;

    if(!empty($_FILES)) {
      $prefijo = substr(md5(uniqid(rand())),0,6);
      $info_archivo = explode(".",$_FILES['foto']['name']);
      $nombre_archivo = $info_archivo[0].'_'.$prefijo.'.'.$info_archivo[1];
      $img = Image::make($_FILES['foto']['tmp_name']);
      $img->save('uploads/productos/' . $nombre_archivo);
      $verificacion = file_exists('uploads/productos/' . $nombre_archivo);
      $model->foto = $nombre_archivo;
    }

    if($verificacion) {
      $rh = $this->productoRepo->upsa($model);
    } else {
      $rh = new ResponseHelper();
      $rh->setResponse(false, 'Error al tratar de subir la Imagen');
      exit(json_encode($rh));
    }

    print_r(
      json_encode($rh)
    );
  }

  public function postActualizar() {
    $verificacion = true;

    ProductoValidation::validate($_POST,$_FILES);

    $model = new Producto;
    $model->id = $_POST['id'];
    $model->descripcion = $_POST['descripcion'];
    $model->marca_id = $_POST['marca_id'];
    $model->costo = $_POST['costo'];
    $model->precio = $_POST['precio'];
    $model->type = true;

    if(!empty($_FILES)) {
      $elementos = $this->productoRepo->obtener($model->id);
      $prefijo = substr(md5(uniqid(rand())),0,6);
      $info_archivo = explode(".",$_FILES['foto']['name']);
      $nombre_archivo = $info_archivo[0].'_'.$prefijo.'.'.$info_archivo[1];
      $img = Image::make($_FILES['foto']['tmp_name']);
      $img->save('uploads/productos/' . $nombre_archivo);
      $verificacion = file_exists('uploads/productos/' . $nombre_archivo);
      if(!empty($elementos['foto'])) {
        unlink('./uploads/productos/'.$elementos['foto']);
      }
      $model->foto = $nombre_archivo;
    }

    if($verificacion) {
      $rh = $this->productoRepo->upsa($model);
    } else {
      $rh = new ResponseHelper();
      $rh->setResponse(false, 'Error al tratar de subir la Imagen');
      exit(json_encode($rh));
    }

    print_r(
      json_encode($rh)
    );
  }

  public function postActuexist() {
    ProductoValidationExist::validate($_POST);

    $model = new Producto;
    $model->id = $_POST['id'];
    $elementos = $this->productoRepo->obtener($model->id);
    $model->entradas = $_POST['entradas'];
    $model->stock = $elementos['stock'] + $model->entradas;
    $model->salidas = 0;

    $rh = $this->productoRepo->savexist($model);

    print_r(
      json_encode($rh)
    );
  }

  public function postEliminar() {
    print_r(
      json_encode(
        $this->productoRepo->eliminar($_POST['id'])
      )
    );
  }
}
?>
