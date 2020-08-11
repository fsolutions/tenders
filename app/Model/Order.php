<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @package App\Model
 */
class Order extends Model
{
  use SoftDeletes;

  public $asYouType = true;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'orders';

  /**
   * The database primary key value.
   *
   * @var string
   */
  protected $primaryKey = 'id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['url', 'name', 'name_visible', 'description', 'additional_urls', 'user_id', 'status', 'paid_till'];

  protected $appends = ['status_stringify', 'can_delete', 'paid_till_formated'];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'created_at' => 'datetime:d.m.Y',
    'updated_at' => 'datetime:d.m.Y H:i',
    'deleted_at' => 'datetime:d.m.Y H:i',
  ];

  /**
   * Get stringify status
   *
   * @param  string  $value
   * @return string
   */
  public function getPaidTillFormatedAttribute()
  {
    $formatedDate = $this->paid_till;

    if ($this->paid_till) {
      // $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->paid_till)->format('d.m.Y');
      $formatedDate = Carbon::createFromFormat('Y-m-d', $this->paid_till)->format('d.m.Y');
    }

    return $formatedDate;
  }

  /**
   * Get stringify status
   *
   * @param  string  $value
   * @return string
   */
  public function getStatusStringifyAttribute()
  {
    $statusString = '';
    switch ($this->status) {
      case 1:
        $statusString = 'Активна';
        break;

      default:
        $statusString = 'Остановлена';
        break;
    }
    return $statusString;
  }

  /**
   * Get delete approve status
   *
   * @return boolean
   */
  public function getCanDeleteAttribute()
  {
    if (Auth::check() && Auth::user()->id == 1) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Get the user that owns the wallet.
   * 
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
