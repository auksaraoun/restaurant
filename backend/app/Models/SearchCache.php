<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchCache extends Model
{
    protected $fillable = [
        'keyword',
        'place_rank',
        'lat',
        'lon',
    ];
}
