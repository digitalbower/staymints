<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['package_id','reviewer_name','reviewer_email','review_description','subscription'];

    public function rating(){

        return $this->hasMany(Rating::class);
    }
}
