<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'osm_id',
        'name',
        'cuisine',
        'lat',
        'lon',
    ];
}
