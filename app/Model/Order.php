<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Order
 *
 * @package App\Model
 */
class Order extends Model
{
  use Notifiable;

  public $asYouType = true;

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

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
  protected $fillable = [
    'user_web_users_id',
    'user_web_users_name',
    'user_web_users_phone',
    'user_web_users_email',
    'order_txt',              // Serialized
    'order_city_id',
    'order_object_id',
    'order_object_name_ext',
    'order_man_name',
    'order_date_of_birth',
    'order_date_of_death',
    'order_gravenumber',
    'order_text_details',
    'number_of_graves',
    'order_start',
    'tarif',
    'itogsum',
    'sended_to_telegram',
    'status',
  ];

  protected $appends = ['status_stringify', 'tarif_stringify', 'status_cssclass', 'ordertxt_stringify', 'can_delete', 'can_access'];

  protected $hidden = [
    'user_web_users_id',
    'user_web_users_name',
    'user_web_users_phone',
    'user_web_users_email'
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'updatetime' => 'datetime:d.m.Y H:i',
    'updated_at' => 'datetime:d.m.Y H:i',
    'created_at' => 'datetime:d.m.Y H:i',
  ];


  /**
   * Get stringify order txt
   *
   * @param  string  $value
   * @return string
   */
  public function getOrdertxtStringifyAttribute()
  {
    $order = unserialize($this->order_txt);
    $orderfinal = "";

    $orderfinal .= '<ul style="padding-left: 15px;">';
    foreach ($order as $key => $orderrow) {
      $orderfinal .= '<li class="usl" data-id="' . $orderrow['id'] . '">' . $orderrow['name'] . '</li>';
    }
    $orderfinal .= '</ul>';

    return $orderfinal;
  }


  /**
   * Get stringify tarif
   *
   * @param  string  $value
   * @return string
   */
  public function getTarifStringifyAttribute()
  {
    $tarifString = '';
    switch ($this->tarif) {
      case 'poisk-mesta-zahoroneniya':
        $tarifString = 'Поиск места захоронения';
        break;
      case 'easy':
        $tarifString = 'Уборка захоронения';
        break;
      case 'extended':
        $tarifString = 'Уборка захоронения (расширенная)';
        break;
      case 'individual':
        $tarifString = 'Индивидуальный заказ';
        break;
      case 'raboty-s-pamyatnikami':
        $tarifString = 'Работы с памятником';
        break;
      case 'uborka-kvartiry-posle-smerti':
        $tarifString = 'Уборка квартиры после смерти';
        break;
      case 'organizaciya-pohoron':
        $tarifString = 'Организация похорон';
        break;
    }

    return $tarifString;
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
      case 'pending':
        $statusString = 'Ожидает оплаты';
        break;
      case '1':
        $statusString = 'Ожидает подтверждения администрацией';
        break;
      case 'waiting_for_capture':
        $statusString = 'Регистрация оплаты';
        break;
      case 'succeeded':
        $statusString = 'Заказ успешно завершен!';  //Оплачен
        break;
      case 'canceled':
        $statusString = 'Отменен';
        break;
      case 'overdue':
        $statusString = 'Отменен'; //Платеж просрочен
        break;
      case '3':
        $statusString = 'Идет прием заявок';
        break;
      case '4':
        $statusString = 'Исполнитель найден';
        break;
      case '5':
        $statusString = 'Заказ исполняется';
        break;
      case '6':
        $statusString = 'Заказ исполнен, ожидает подтверждения';
        break;
      case '7':
        $statusString = 'Заказ отменен заказчиком';
        break;
      case '8':
        $statusString = 'Заказ отправлен на доработку';
        break;
      case '9':
        $statusString = 'Заказ успешно завершен!';
        break;
      case '10':
        $statusString = 'Заказ отменен исполнителем';
        break;
      case '11':
        $statusString = 'Отменен администрацией';
        break;
    }

    return $statusString;
  }


  /**
   * Get css class by status
   *
   * @param  string  $value
   * @return string
   */
  public function getStatusCssclassAttribute()
  {
    $trclass = '';

    switch ($this->status) {
      case 'pending':
        $trclass = "pending";
        break;
      case '1':
        $trclass = "pending";
        break;
      case 'waiting_for_capture':
        $trclass = "waiting_for_capture";
        break;
      case 'succeeded':
        $trclass = "success";
        break;
      case 'canceled':
        $trclass = "canceled";
        break;
      case 'overdue':
        $trclass = "canceled";
        break;
      case '3':
        $trclass = "success";
        break;
      case '4':
        $trclass = "success";
        break;
      case '5':
        $trclass = "success";
        break;
      case '6':
        $trclass = "success";
        break;
      case '7':
        $trclass = "success";
        break;
      case '8':
        $trclass = "success";
        break;
      case '9':
        $trclass = "goodend";
        break;
      case '10':
        $trclass = "canceled";
        break;
      case '11':
        $trclass = "canceled";
        break;
    }

    return $trclass;
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
   * Get access approve status
   *
   * @return boolean
   */
  public function getCanAccessAttribute()
  {
    if (Auth::check() && Auth::user()->id) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Order City
   * 
   * @return mixed
   */
  public function city()
  {
    return $this->hasOne('App\Model\ModxSiteContent', 'id', 'order_city_id');
  }

  /**
   * Order Graveyard
   * 
   * @return mixed
   */
  public function graveyard()
  {
    return $this->hasOne('App\Model\ModxSiteContent', 'id', 'order_object_id');
  }

  /**
   * User Reactions on Order
   * 
   * @return mixed
   */
  public function react()
  {
    // return $this->hasManyThrough('App\User', 'App\Model\Order', 'order_id', 'user_id');
    return $this->belongsToMany('App\User')->using('App\Pivots\Reactions');
  }
}
