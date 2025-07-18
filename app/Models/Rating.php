<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
   protected $fillable = ['review_id','review_type','review_rating'];
  
}
