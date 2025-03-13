<?php
namespace App\Controllers;

use App\Config\{Auth, Controller};
use App\Helpers\{UrlHelper};
use App\Models\Repositories\UsuarioRepository;

class AuthController extends Controller {
  private $usuarioRepo;

  public function __construct() {
    if(Auth::isLoggedIn()) {
      UrlHelper::redirect();
    }

    parent::__construct();
    $this->usuarioRepo = new UsuarioRepository;
  }

  public function getIndex() {
    return $this->render('auth/index.twig', [
      'title' => 'Autenticación'
    ]);
  }

  public function postSignin() {
    $rh = $this->usuarioRepo->autenticar(
      strtolower($_POST['email']),
      $_POST['password']
    );

    if($rh->response) {
      $rh->href = 'home';
    }

    print_r(
      json_encode($rh)
    );
  }

  public function getSignout() {
    Auth::destroy();

    UrlHelper::redirect('');
  }
}
?>
