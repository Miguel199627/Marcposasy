<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;

class ProductoValidationExist {
    public static function validate (array $model) {
        try{
          $v = v::key('entradas', v::intVal()->notEmpty());
          $v->assert($model);
        } catch (\Exception $e) {
          $rh = new ResponseHelper();
          $rh->setResponse(false, null);
          $rh->validations = $e->findMessages([
              'entradas' => 'El campo {{name}} es requerido',
          ]);

          exit(json_encode($rh));
        }
    }
}
?>
