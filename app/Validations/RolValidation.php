<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;

class RolValidation {
    public static function validate (array $model) {
        try{
          $v = v::key('descripcion', v::stringType()->notEmpty());
          $v->assert($model);
        } catch (\Exception $e) {
          $rh = new ResponseHelper();
          $rh->setResponse(false, null);
          $rh->validations = $e->findMessages([
              'descripcion' => 'el campo {{name}} es requerido'
          ]);

          exit(json_encode($rh));
        }
    }
}
?>
