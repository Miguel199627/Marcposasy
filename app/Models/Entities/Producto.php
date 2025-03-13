<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model {
  protected $table = 'producto';

  public function marca() {
    return $this->belongsTo('App\Models\Entities\Marca');
  }
}
