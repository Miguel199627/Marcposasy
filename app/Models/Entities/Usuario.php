<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {
  protected $table = 'usuario';

  public function rol() {
    return $this->belongsTo('App\Models\Entities\Rol');
  }
}
