<?php
namespace App\Models\Repositories;

use App\Config\Auth;
use App\Models\Entities\Usuario;
use App\Helpers\ResponseHelper;
use Exception;


class UsuarioRepository {
  private $usuario;

  public function __construct() {
    $this->usuario = new Usuario;
  }

  public function upsa(Usuario $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->usuario->id = $model->id;
      $this->usuario->nombres = $model->nombres;
      $this->usuario->apellidos = $model->apellidos;
      $this->usuario->email = $model->email;
      $this->usuario->estado = $model->estado;

      if(!empty($model->contraseña)) {
        $this->usuario->contraseña = password_hash($model->contraseña, PASSWORD_BCRYPT, ['cost' => 12]);
      }

      $this->usuario->rol_id = $model->rol_id;

      if($model->type) {
        $this->usuario->exists = true;
        $rh->setResponse(true,'Usuario actualizado con exito');
      } else {
        $rh->setResponse(true,'Usuario ingresado con exito');
      }

      $this->usuario->save();

    } catch (Exception $e) {
        $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function perfil(Usuario $model) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $this->usuario->id = $model->id;
      $this->usuario->nombres = $model->nombres;
      $this->usuario->apellidos = $model->apellidos;
      $this->usuario->email = $model->email;
      $this->usuario->foto = $model->foto;

      if(!empty($model->contraseña)) {
        $this->usuario->contraseña = password_hash($model->contraseña, PASSWORD_BCRYPT, ['cost' => 12]);
      }

      $this->usuario->exists = true;

      $this->usuario->save();

      $rh->setResponse(true,'Perfil actualizado con exito');
    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function obtener($search,$dato) {
    try {
      return $this->usuario->select('usuario.id','nombres','apellidos','email','foto','estado','contraseña','rol_id','descripcion','permisos')
      ->leftJoin('rol', 'usuario.rol_id', '=', 'rol.id')
      ->where($search,$dato)
      ->first();
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function listar() {
    try {
      $result = $this->usuario->orderBy('id','ASC')->get();
      foreach ($result as $m) {
          $m->rol = $m->rol;
      }
      return $result;
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }

  public function autenticar(string $correo, string $password) : ResponseHelper {
    $rh = new ResponseHelper();

    try {
      $row = $this->usuario->select('usuario.id','nombres','apellidos','email','foto','estado','contraseña','rol_id','descripcion','permisos')
      ->leftJoin('rol', 'usuario.rol_id', '=', 'rol.id')
      ->where('email', $correo)
      ->first();
      if(is_object($row) && password_verify($password,$row->contraseña)) {
        if($row->estado == 1) {
          Auth::signIn([
              'id' => $row->id,
              'nombres' => $row->nombres,
              'apellidos' => $row->apellidos,
              'email' => $row->email,
              'foto' => $row->foto,
              'rol_id' => $row->rol_id,
              'descripcion' => $row->descripcion,
              'permisos' => $row->permisos,
          ]);
          $rh->setResponse(true);
        } else {
          $rh->setResponse(false, 'Usuario inactivo o deshabilitado');
        }
      } else {
        $rh->setResponse(false, 'Datos incorrectos, intente de nuevo');
      }
    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }

  public function eliminar(int $id) : ResponseHelper {
    $rh = new ResponseHelper;
    try {
      $resul = $this->obtener('usuario.id',$id);
      $this->usuario->destroy($id);
      if(!empty($resul['foto'])) {
          unlink("./uploads/usuarios/".$resul['foto']);
      }
      $rh->setResponse(true,'Usuario eliminado con exito');
    } catch (Exception $e) {
      $rh->setResponse(false,$e->getMessage());
    }

    return $rh;
  }
}
?>
