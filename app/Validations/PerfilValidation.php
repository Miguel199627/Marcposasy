<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;

class PerfilValidation {
    public static function validate (array $model,array $files) {
        $rh = new ResponseHelper();
        $foto_validacion = '';
        if(!empty($files)){
          $type = $files['foto']['type'];
          if($type != "image/png" && $type != "image/x-png" && $type != "image/jpeg" && $type != "image/x-jpeg"){
            $foto_validacion = 'Seleccione una imagen con formato JPG, JPGE o PNG';
          } else if ($files['foto']['size'] > 1000000){
            $foto_validacion = 'Seleccione una imagen con un peso menor a 1MB';
          }
        }
        try{
          $v = v::key('nombres', v::stringType()->notEmpty())
              ->key('apellidos', v::stringType()->notEmpty())
              ->key('email', v::email()->notEmpty())
              ->keyValue('con_contraseña', 'equals', 'contraseña');
          $v->assert($model);
        } catch (\Exception $e) {
          $rh->validations = $e->findMessages([
              'nombres' => 'El campo nombre es requerido',
              'apellidos' => 'El campo apellido es requerido',
              'email' => 'El campo {{name}} es requerido',
              'con_contraseña' => 'Las contraseñas no coinciden'
          ]);
        }
        if(!empty($rh->validations) || $foto_validacion !== ''){
          $rh->validations['foto'] = $foto_validacion;
          $rh->setResponse(false, null);
          exit(json_encode($rh));
        }
    }
}
?>
