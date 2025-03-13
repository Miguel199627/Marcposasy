<?php
/* Controllers */
$router->group(['before' => 'auth'], function($router){
  $router->controller('/home', 'App\\Controllers\\HomeController');
  $router->controller('/perfil', 'App\\Controllers\\PerfilController');
  $router->group(['before' => 'ventas'], function($router){
    $router->controller('/venta', 'App\\Controllers\\VentaController');
  });
  $router->group(['before' => 'clientes'], function($router){
    $router->controller('/cliente', 'App\\Controllers\\ClienteController');
  });
  $router->group(['before' => 'productos'], function($router){
    $router->controller('/producto', 'App\\Controllers\\ProductoController');
  });
  $router->group(['before' => 'usuarios'], function($router){
    $router->controller('/usuario', 'App\\Controllers\\UsuarioController');
  });
  $router->group(['before' => 'facturas'], function($router){
    $router->controller('/factura', 'App\\Controllers\\FacturaController');
  });
  $router->group(['before' => 'ventaspormes'], function($router){
    $router->controller('/ventaspormes', 'App\\Controllers\\VentasPorMesController');
  });
  $router->group(['before' => 'productospormes'], function($router){
    $router->controller('/productospormes', 'App\\Controllers\\ProductosPorMesController');
  });
  $router->group(['before' => 'marcas'], function($router){
    $router->controller('/marca', 'App\\Controllers\\MarcaController');
  });
  $router->group(['before' => 'roles'], function($router){
    $router->controller('/rol', 'App\\Controllers\\RolController');
  });
});

$router->controller('/auth', 'App\\Controllers\\AuthController');
$router->get('/', function(){
    if(!\App\Config\Auth::isLoggedIn()){
        \App\Helpers\UrlHelper::redirect('auth');
    } else {
        \App\Helpers\UrlHelper::redirect('home');
    }
});
?>
