<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageItinerary extends Model
{
    use SoftDeletes;
    protected $fillable = ['package_id','day_number','title','description'];
}
