<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Repositories\ProductosPorMesRepository;

class ProductosPorMesController extends Controller {
  private $productospormesRepo;

  public function __construct() {
    parent::__construct();
    $this->productospormesRepo = new ProductosPorMesRepository;
  }

  public function getIndex() {
    return $this->render('productospormes/index.twig', [
      'title' => 'Productos Por Mes'
    ]);
  }

  public function postGrid() {
    $verify = $this->productospormesRepo->listar($_POST['año'],$_POST['mes']);
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function getPdf($y = null,$m = null) {
    $año = $y == 'null' ? null : $y;
    $model = $this->productospormesRepo->listar($año,$m);
    $dompdf = new Dompdf;

    $dompdf->loadHtml(
      $this->render('productospormes/pdf.twig', [
        'model' => $model
      ])
    );

    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $dompdf->stream('reporte_productos.pdf',array('Attachment'=>0));
  }
}
?>
