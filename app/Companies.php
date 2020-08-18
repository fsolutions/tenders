<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    /**
     * @inheritdoc
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * @inheritdoc
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_as_role',
        'value',
        'phone1',
        'phone2',
        'email1',
        'email2',
        'website',
        'description',
        'coords',
        'radius',
        'address_from_map',
        'city_from_map',
        'logo',
        'inn',
        'kpp',
        'ogrn',
        'ogrn_date',
        'hid',
        'type',
        'name_full_with_opf',
        'name_short_with_opf',
        'name_full',
        'name_short',
        'opf_short',
        'management_name',
        'management_post',
        'address_value',
        'address_unrestricted_value',
        'state_actuality_date',
        'state_registration_date',
        'state_liquidation_date',
        'state_status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i'
    ];
}
