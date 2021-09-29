<?php

namespace App;

use App\Companies;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * User Companies
     * 
     * @return mixed
     */
    public function companies()
    {
        return $this->belongsToMany(Companies::class, 'users_companies', 'user_id', 'companies_id');
    }

    /**
     * User Reactions on Order
     * 
     * @return mixed
     */
    public function react()
    {
        return $this->belongsToMany('App\Model\Order', 'order_user', 'user_id', 'order_id');
    }
}
