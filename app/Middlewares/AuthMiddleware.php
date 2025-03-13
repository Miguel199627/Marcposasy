<?php
namespace App\Middlewares;

use App\Helpers\UrlHelper,
    App\Config\Auth;

class AuthMiddleware {
    public static function isLoggedIn() {
        if(!Auth::isLoggedIn()) {
            UrlHelper::redirect('auth');
        }
    }
}
