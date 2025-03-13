<?php
// Auth Filter

$router->filter('auth', function(){
  \App\Middlewares\AuthMiddleware::isLoggedIn();
});

$router->filter('ventas', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('ventas')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('facturas', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('facturas')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('ventaspormes', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('ventaspormes')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('productospormes', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('productospormes')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('usuarios', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('usuarios')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('clientes', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('clientes')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('productos', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('productos')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('marcas', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('marcas')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

$router->filter('roles', function(){
    if(!\App\Middlewares\RoleMiddleware::Permissions('roles')){
        \App\Helpers\UrlHelper::redirect('');
    };
});

?>
