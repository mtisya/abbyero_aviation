<?php

// app/Models/Skydiving.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skydiving extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'location', 'date', 'time', 'available_slots', 'image'
    ];
}
