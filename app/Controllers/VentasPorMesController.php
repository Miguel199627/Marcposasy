<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Repositories\VentasPorMesRepository;

class VentasPorMesController extends Controller {
  private $ventaspormesRepo;

  public function __construct() {
    parent::__construct();
    $this->ventaspormesRepo = new VentasPorMesRepository;
  }

  public function getIndex() {
    return $this->render('ventaspormes/index.twig', [
      'title' => 'Ventas Por Mes'
    ]);
  }

  public function postGrid() {
    $verify = $this->ventaspormesRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function getPdf() {
    $model = $this->ventaspormesRepo->listar();
    $dompdf = new Dompdf;

    $dompdf->loadHtml(
      $this->render('ventaspormes/pdf.twig', [
        'model' => $model
      ])
    );

    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $dompdf->stream('reporte_ventas.pdf',array('Attachment'=>0));
  }
}
?>
