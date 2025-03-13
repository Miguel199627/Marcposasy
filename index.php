<?php
require_once 'vendor/autoload.php';
App\Config\ServicesContainer::setConfig(
    require_once 'app/Config/database.php'
);
App\Config\ServicesContainer::initializeDbContext();
$config = App\Config\ServicesContainer::getConfig();
date_default_timezone_set($config['timezone']);
$base_url = '';
$base_folder = strtolower(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']));
if (isset($_SERVER['HTTP_HOST']))
{
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
    $base_url .= '://'. $_SERVER['HTTP_HOST'];
    $base_url .= $base_folder;
}
define('_BASE_HTTP_', $base_url);
define('_BASE_PATH_', __DIR__ . '/');
define('_APP_PATH_', __DIR__ . '/app/');
define('_CACHE_PATH_', __DIR__ . '/cache/');
define('_CURRENT_URI_', $base_folder === '/' ? $_SERVER['REQUEST_URI'] : str_replace($base_folder, '', $_SERVER['REQUEST_URI']));
if($config['environment'] === 'stop') {
    exit('Website is current down ..');
}

if($config['environment'] === 'prod') {
    error_reporting(0);
}
$router = new Phroute\Phroute\RouteCollector();

require_once 'app/filters.php';
require_once 'app/routes.php';

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    _CURRENT_URI_
);

echo $response;
?>
