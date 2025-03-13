<?php
namespace App\Models\Repositories;

use App\Models\Entities\SqlViews\ReporteVenta;
use App\Helpers\ResponseHelper;
use Exception;

class VentasPorMesRepository {
  private $reporteventa;

  public function __construct() {
    $this->reporteventa = new ReporteVenta;
  }

  public function listar() {
    try {
      return $this->reporteventa->orderBy('anio','DESC')->get();
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }
}
?>
