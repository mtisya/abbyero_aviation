<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Glider extends Model
{
    protected $fillable = [
        'model', 'registration', 'capacity', 'rental_price', 'status', 'description', 'image'
    ];
}
