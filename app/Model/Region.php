<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'regions';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'code'
    ];

    /**
     * Customize format
     *
     * @var array
     */
    protected $casts = [];
}
