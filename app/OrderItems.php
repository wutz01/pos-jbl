<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
  protected $table = 'order_items';

  public function order () {
    return $this->belongsTo('App\Orders', 'orderId');
  }

  public function inventory () {
    return $this->belongsTo('App\Inventory', 'productId');
  }

  public function scopeInfo ($query) {
    return $query->with('inventory');
  }
}
