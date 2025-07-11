<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainSeo extends Model
{
    protected $fillable = ['page_url','meta_title', 'meta_description','og_title','og_description','og_image','schema'];

}
