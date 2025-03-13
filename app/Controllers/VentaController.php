<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use App\Config\Auth;
use App\Config\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Entities\{Factura,MovimientoFactura};
use App\Models\Repositories\{FacturaRepository,ProductoRepository};

class VentaController extends Controller {
  private $facturaRepo;
  private $productoRepo;

  public function __construct() {
    parent::__construct();
    $this->facturaRepo = new FacturaRepository;
    $this->productoRepo = new ProductoRepository;
  }

  public function getIndex() {
    return $this->render('venta/index.twig', [
      'title' => 'Ventas'
    ]);
  }

  public function postGuardar() {
    $factura = $_POST['factura'];
    $datos = Auth::getCurrentUser();
    $detalle = [];
    $exist = [];

    $model = new Factura;
    $model->cliente_id = $factura['cliente_id'];
    $model->usuario_id = $datos->id;
    $model->iva = $factura['iva'];
    $model->total = $factura['total'];
    $model->neto = $factura['neto'];
    $model->estado = 1;

    foreach ($_POST['movimiento'] as $d) {
      $d = (object)$d;

      $cd = new MovimientoFactura;
      $cd->producto_id = $d->producto_id;
      $cd->cantidad = $d->cantidad;
      $cd->precio = $d->precio;
      $cd->total = $d->total;

      $detalle[] = $cd;
      $exist[] = $d;
    }

    $rh_f = $this->facturaRepo->guardar($model, $detalle);

    if(!$rh_f->response) {
      exit(json_encode($rh_f));
    }

    $rh_e = $this->productoRepo->updatexist($exist,false);

    if($rh_e->response){
      $rh = array('response' => true, 'id' => $rh_f->id);
    } else {
      $rh_d = $this->facturaRepo->eliminar($rh_f->id);
      $rh = $rh_d->response ? array('response' => false, 'message' => $rh_e->message) : array('response' => false, 'message' => $rh_d->message);
    }

    print_r(
      json_encode($rh)
    );
  }

  public function getPdf($id) {
    $model = $this->facturaRepo->obtener($id);
    $dompdf = new Dompdf;

    $dompdf->loadHtml(
      $this->render('venta/pdf.twig', [
        'model' => $model
      ])
    );

    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $dompdf->stream('factura_'.$model->id.'.pdf',array('Attachment'=>0));
  }
}
?>
