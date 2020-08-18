<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Reactions extends Pivot
{
  protected $table = 'order_user';

  public function orders()
  {
    return $this->belongsTo('App\Model\Order', 'order_id');
  }
  public function users()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
}
