<?php
namespace App\Helpers;

class UrlHelper {
    public static function base(string $route = '') : string {
        return _BASE_HTTP_ . $route;
    }

    public static function bower_components(string $route = '') : string {
        return _BASE_HTTP_ . 'bower_components/' . $route;
    }

    public static function assets(string $route = '') : string {
        return _BASE_HTTP_ . 'assets/' . $route;
    }

    public static function uploads(string $route = '') : string {
        return _BASE_HTTP_ . 'uploads/' . $route;
    }

    public static function redirect(string $url = '') {
        header(sprintf("Location: %s%s", _BASE_HTTP_, $url));
    }
}
?>
