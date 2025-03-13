<?php
namespace App\Models\Repositories;

use App\Models\Entities\SqlViews\ReporteProducto;
use App\Helpers\ResponseHelper;
use Exception;

class ProductosPorMesRepository {
  private $reporteproducto;

  public function __construct() {
    $this->reporteproducto = new ReporteProducto;
  }

  public function listar($y = null, $m = null) {
    try {
      if(!empty($y) && !empty($m)) {
        return $this->reporteproducto->orderBy('anio','DESC')->where('anio', $y)->where('mes', $m)->get();
      } else if(!empty($y) && empty($m)) {
        return $this->reporteproducto->orderBy('anio','DESC')->where('anio', $y)->get();
      } else if(empty($y) && !empty($m)) {
        return $this->reporteproducto->orderBy('anio','DESC')->where('mes', $m)->get();
      } else {
        return $this->reporteproducto->orderBy('anio','DESC')->get();
      }
    } catch (Exception $e) {
      $rh = new ResponseHelper;
      return $rh->setResponse(false,$e->getMessage());
    }
  }
}
?>
