<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;

class UsuarioValidation {
    public static function validate (array $model) {
        try{
          $v = v::key('nombres', v::stringType()->notEmpty())
            ->key('apellidos', v::stringType()->notEmpty())
            ->key('email', v::email()->notEmpty())
            ->keyValue('con_contraseña', 'equals', 'contraseña')
            ->key('rol_id',  v::intVal()->notEmpty());
          if(empty($model['id'])){
            $v->key('contraseña', V::notEmpty());
          }
          $v->assert($model);
        } catch (\Exception $e) {
          $rh = new ResponseHelper();
          $rh->setResponse(false, null);
          $rh->validations = $e->findMessages([
              'nombres' => 'el campo nombre es requerido',
              'apellidos' => 'el campo apellido es requerido',
              'email' => 'el campo {{name}} es requerido',
              'contraseña' => 'el campo {{name}} es requerido',
              'con_contraseña' => 'Las contraseñas no coinciden',
              'rol_id' => 'el campo rol es requerido',
          ]);

          exit(json_encode($rh));
        }
    }
}
?>
