<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class InboxMail
 *
 * @package App\Model
 */
class InboxMail extends Model
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
  protected $table = 'inbox_mail';

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
    'imap_uid',
    'sended_to_telegram',
  ];

  protected $appends = [];

  protected $hidden = [];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'updated_at' => 'datetime:d.m.Y H:i',
    'created_at' => 'datetime:d.m.Y H:i',
  ];
}
