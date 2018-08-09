<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryTrail extends Model
{
  protected $table = 'inventory_trails';

  public function inventory () {
    return $this->belongsTo('App\Inventory');
  }

  public function userAttached () {
    return $this->belongsTo('App\User', 'updatedBy');
  }
}
