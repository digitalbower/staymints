<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GetQuote extends Model
{
    protected $fillable = ['first_name','last_name','email','phone','agree_terms','requirments'];
}
