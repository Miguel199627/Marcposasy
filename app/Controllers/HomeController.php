<?php
namespace App\Controllers;

use App\Models\Repositories\LandingRepository;
Use App\Config\Controller;

class HomeController extends Controller {
  private $landingRepo;

  public function __construct() {
    parent::__construct();
    $this->landingRepo = new LandingRepository();
  }

  public function getIndex() {
    return $this->render('home/index.twig', [
        'title' => 'Inicio',
        'model' => $this->landingRepo->obtener()
    ]);
  }
}
?>
