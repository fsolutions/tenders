<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TGUserRegion extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tg_user_regions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tg_user_id',
        'regions_id'
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
    public function tg_user()
    {
        return $this->hasOne(TGUser::class, 'tg_user_id', 'tg_user_id');
    }

    /**
     * Relationships One to One
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function info()
    {
        return $this->hasOne(Region::class, 'id', 'regions_id');
    }
}
