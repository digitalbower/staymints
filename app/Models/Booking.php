<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
            'user_id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'package_id',
            'booking_month',
            'booking_year',
            'adults_quantity',
            'children_quantity',
            'infants_quantity',
            'services',
            'starting_price',
    ];
}
