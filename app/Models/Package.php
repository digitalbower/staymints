<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'package_name', 
        'slug', 
        'country_id',
        'category_id',
        'tag_id',
        'sales_person_id',
        'unit_type_id',
        'starting_price',
        'slide_show',
        'gallery',
        'image',
        'video',
        'inclusions',
        'duration',
        'group_size',
        'overview',
        'highlights',
        'included',
        'excluded',
        'extra_services',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'schema',
        'status',
        'recommendation',
        'itinerary_desc'
    ];
    protected $casts = [
    'inclusions' => 'array',
    'included' => 'array',
    'excluded' => 'array',
    'gallery' => 'array',
    'slide_show' => 'array',
    'extra_services'=>'array'
];
    public function itineraries(){

        return $this->hasMany(PackageItinerary::class);
    }
    public function tag(){
        return $this->belongsTo(Tag::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function type(){
        return $this->belongsTo(UnitType::class,'unit_type_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function reviews(){

        return $this->hasMany(Review::class);
    }
}
