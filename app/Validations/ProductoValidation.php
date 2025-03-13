<?php
namespace App\Validations;
use Respect\Validation\Validator as v;
use App\Helpers\ResponseHelper;

class ProductoValidation {
  public static function validate (array $model,array $files) {
    $rh = new ResponseHelper();
    $foto_validacion = '';
    if(!empty($files)) {
      $type = $files['foto']['type'];
      if($type != "image/png" && $type != "image/x-png" && $type != "image/jpeg" && $type != "image/x-jpeg") {
        $foto_validacion = 'Seleccione una imagen con formato JPG, JPGE o PNG';
      } else if ($files['foto']['size'] > 2000000){
        $foto_validacion = 'Seleccione una imagen con un peso menor a 2MB';
      }
    }
    try{
      $v = v::key('descripcion', v::stringType()->notEmpty())
          ->key('marca_id', v::intVal()->notEmpty())
          ->key('costo', v::intVal()->notEmpty())
          ->key('precio', v::intVal()->notEmpty());
      $v->assert($model);
    } catch (\Exception $e) {
      $rh->validations = $e->findMessages([
          'descripcion' => 'El campo {{name}} es requerido',
          'marca_id' => 'El campo marca es requerido',
          'costo' => 'El campo {{name}} es requerido',
          'precio' => 'El campo {{name}} es requerido'
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
