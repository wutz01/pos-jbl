<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
  protected $table = 'orders';

  public function items () {
    return $this->hasMany('App\OrderItems', 'orderId');
  }

  public function user () {
    return $this->belongsTo('App\User', 'serverId');
  }
}
