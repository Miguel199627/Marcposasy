<?php
namespace App\Helpers;

class ResponseHelper {
	public $result      = null;
	public $response    = false;
	public $message     = 'Ocurrio un error inesperado';
	public $href        = null;
	public $function    = null;
	public $filter      = null;
  	public $validations = [];

	public function setResponse($response, $m = '') {
		$this->response = $response;
		$this->message = $m;

    return $this;
	}

    public function setErrors($error) {
			$this->validations = $error;
      $this->response = 'Error de validación';

      return $this;
    }
}
