<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModxSiteContent extends Model
{

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
    protected $table = 'modx_site_content';

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
    protected $visible = [
        'pagetitle',
        'introtext'
    ];
}
