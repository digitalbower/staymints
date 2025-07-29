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
            'agree_terms',
            'requirements'
    ];
    protected $casts = [
        'requirements' => 'array',
    ];
}
