<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model {
  protected $table = 'factura';
  
  public function detalle() {
   return $this->hasMany('App\Models\Entities\MovimientoFactura');
  }

  public function cliente() {
    return $this->belongsTo('App\Models\Entities\Cliente');
  }
  public function usuario() {
    return $this->belongsTo('App\Models\Entities\Usuario');
  }
}
