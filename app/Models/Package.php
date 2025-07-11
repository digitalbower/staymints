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
        'recommendation'
    ];
    protected $casts = [
    'inclusions' => 'array',
    'included' => 'array',
    'excluded' => 'array',
    'gallery' => 'array',
    'extra_services'=>'array'
];
    public function itineraries(){

        return $this->hasMany(PackageItinerary::class);
    }
    
}
