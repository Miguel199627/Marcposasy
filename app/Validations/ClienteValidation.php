<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;

class ClienteValidation {
    public static function validate (array $model) {
        try{
          $v = v::key('id', v::intVal()->notEmpty())
            ->key('nombres', v::stringType()->notEmpty())
            ->key('apellidos', v::stringType()->notEmpty())
            ->key('direccion', v::stringType()->notEmpty())
            ->key('telefono', v::stringType()->notEmpty());
          $v->assert($model);
        } catch (\Exception $e) {
          $rh = new ResponseHelper();
          $rh->setResponse(false, null);
          $rh->validations = $e->findMessages([
              'id' => 'el campo identificación es requerido',
              'nombres' => 'el campo nombre es requerido',
              'apellidos' => 'el campo apellido es requerido',
              'direccion' => 'el campo {{name}} es requerido',
              'telefono' => 'el campo {{name}} es requerido'
          ]);

          exit(json_encode($rh));
        }
    }
}
?>
