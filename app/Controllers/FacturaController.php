<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\Factura;
use App\Models\Repositories\{FacturaRepository,ProductoRepository};

class FacturaController extends Controller {
  private $facturaRepo;
  private $productoRepo;

  public function __construct() {
    parent::__construct();
    $this->facturaRepo = new FacturaRepository;
    $this->productoRepo = new ProductoRepository;
  }

  public function getIndex() {
    return $this->render('factura/index.twig', [
      'title' => 'Facturas'
    ]);
  }

  public function postGrid() {
    $verify = $this->facturaRepo->listar();
    if(!empty($verify->response)) {
      echo json_encode($rh->setResponse(false,$verify->message));
    } else {
      echo json_encode(array('data'=>$verify),JSON_UNESCAPED_UNICODE);
    }
  }

  public function getPdf($id) {
    $model = $this->facturaRepo->obtener($id);
    $dompdf = new Dompdf;

    $dompdf->loadHtml(
      $this->render('factura/pdf.twig', [
        'model' => $model
      ])
    );

    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $dompdf->stream('factura_'.$model->id.'.pdf',array('Attachment'=>0));
  }

  public function postAnular() {
    $rh_f = $this->facturaRepo->anular($_POST['id'],true);

    if(!$rh_f->response) {
      exit(json_encode($rh_f));
    }

    $model = $this->facturaRepo->obtener($_POST['id']);
    $rh_d = $this->productoRepo->updatexist($model->detalle,true);

    if($rh_d){
      $rh = $rh_f;
    } else {
      $rh_a = $this->facturaRepo->anular($_POST['id'],false);
      $rh = $rh_a ? $rh_d : $rh_a;
    }

    print_r(
      json_encode($rh)
    );
  }
}
?>
