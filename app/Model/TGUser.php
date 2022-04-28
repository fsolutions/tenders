<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TGUser extends Model
{
    use SoftDeletes;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tg_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tg_user_id',
        'user_id',
        'tg_username',
        'only_regions'
    ];

    /**
     * Customize format
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i'
    ];

    /**
     * Relationships One to One
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Relationships One to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function regions()
    {
        return $this->hasMany(TGUserRegion::class, 'tg_user_id', 'tg_user_id')->with(['info']);
    }
}
