<?php
namespace App\Controllers;

use App\Config\Auth;
use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Usuario;
use App\Validations\PerfilValidation;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Repositories\UsuarioRepository;

class PerfilController extends Controller {
  private $usuarioRepo;

  public function __construct() {
    parent::__construct();
    $this->usuarioRepo = new UsuarioRepository;
  }

  public function getIndex() {
    $model = Auth::getCurrentUser();
    return $this->render('perfil/index.twig', [
      'title' => 'Perfil',
      'model' => $model
    ]);
  }

  public function postActualizar() {
    $verificacion = true;

    PerfilValidation::validate($_POST,$_FILES);

    $rh = new ResponseHelper();

    $datos = Auth::getCurrentUser();

    $model = new Usuario;
    $model->id = $datos->id;
    $model->nombres = $_POST['nombres'];
    $model->apellidos = $_POST['apellidos'];
    $model->email = strtolower($_POST['email']);

    $elementos = $this->usuarioRepo->obtener('usuario.id',$model->id);
    if($model->email != $elementos['email'] && !empty($this->usuarioRepo->obtener('email',$model->email))) {
      $rh->setResponse(false, 'El correo ingresado ya esta ocupado');
      exit(json_encode($rh));
    }

    if(!empty($_FILES)) {
      if(!empty($elementos['foto'])) {
        unlink('./uploads/usuarios/'.$elementos['foto']);
      }
      $prefijo = substr(md5(uniqid(rand())),0,6);
      $info_archivo = explode(".",$_FILES['foto']['name']);
      $nombre_archivo = $info_archivo[0].'_'.$prefijo.'.'.$info_archivo[1];
      $img = Image::make($_FILES['foto']['tmp_name']);
      $img->save('uploads/usuarios/' . $nombre_archivo);
      $verificacion = file_exists('uploads/usuarios/' . $nombre_archivo);
      $model->foto = $nombre_archivo;
    } else {
      $model->foto = $elementos['foto'];
    }

    $model->contraseña = $_POST['contraseña'];

    if($verificacion){
      $rh = $this->usuarioRepo->perfil($model);
    } else {
      $rh->setResponse(false, 'Error al tratar de subir la Imagen');
      exit(json_encode($rh));
    }

    if($rh->response) {
      Auth::signIn([
        'id' => $model->id,
        'nombres' => $model->nombres,
        'apellidos' => $model->apellidos,
        'email' => $model->email,
        'foto' => $model->foto,
        'rol_id' => $elementos['rol_id'],
        'descripcion' => $elementos['descripcion'],
        'permisos' => $elementos['permisos'],
      ]);
    }

    print_r(
      json_encode($rh)
    );
  }
}
?>
