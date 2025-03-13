<?php
namespace App\Config;

class Controller {
    protected $provider;

    public function __construct() {
        $config = ServicesContainer::getConfig();

        $loader = new \Twig\Loader\FilesystemLoader(_APP_PATH_ . 'views/');

        $this->provider = new \Twig\Environment($loader, array(
            'cache' => !$config['cache'] ? false : _CACHE_PATH_,
            'debug' => true,
        ));

        $this->provider->addExtension(new \Twig\Extension\DebugExtension());

        // My custom filters
        $this->addCustomFilters();
    }

    private function addCustomFilters(){
        // Filter
        $this->provider->addFilter(new \Twig\TwigFilter('url', ['App\\Helpers\\UrlHelper', 'base']));
        $this->provider->addFilter(new \Twig\TwigFilter('bower_components', ['App\\Helpers\\UrlHelper', 'bower_components']));
        $this->provider->addFilter(new \Twig\TwigFilter('assets', ['App\\Helpers\\UrlHelper', 'assets']));
        $this->provider->addFilter(new \Twig\TwigFilter('uploads', ['App\\Helpers\\UrlHelper', 'uploads']));

        // Functions
        $this->provider->addFunction(new \Twig\TwigFunction('user', ['App\\Config\\Auth', 'getCurrentUser']));
        $this->provider->addFunction(new \Twig\TwigFunction('Permissions', ['App\\Middlewares\\RoleMiddleware', 'Permissions']));
    }

    protected function render(string $view, array $data = []) : string {
        return $this->provider->render($view, $data);
    }
}
?>
