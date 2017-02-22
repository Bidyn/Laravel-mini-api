<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeoLocation extends Model
{
    protected $fillable = [
        'lat',
        'lon',
        'city',
        'country'
    ];

}
