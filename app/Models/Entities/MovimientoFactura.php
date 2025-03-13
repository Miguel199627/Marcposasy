<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class MovimientoFactura extends Model {
  protected $table = 'movimientofactura';

  public function producto() {
    return $this->belongsTo('App\Models\Entities\Producto');
  }
}
