<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
  protected $table = 'inventory';

  public function trails () {
    return $this->hasMany('App\InventoryTrail', 'productId');
  }

  public function orders () {
    return $this->hasMany('App\Orders');
  }
}
