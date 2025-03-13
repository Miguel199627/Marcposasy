<?php
namespace App\Middlewares;

use App\Config\Auth;

class RoleMiddleware {

    public static function Permissions($search) {
      $user = Auth::getCurrentUser();
      $modulos = explode(",",$user->permisos);
      return in_array($search,$modulos);
    }
    
}
