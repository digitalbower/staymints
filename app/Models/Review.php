<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $fillable = ['package_id','reviewer_name','reviewer_email','review_description','subscription','admin_reply'];

    public function rating(){

        return $this->hasMany(Rating::class);
    }
    public function package(){
        return $this->belongsTo(Package::class,'package_id');
    }
}
