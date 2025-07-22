<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
   use SoftDeletes;
   protected $fillable = ['review_id','review_type','review_rating'];
  
}
