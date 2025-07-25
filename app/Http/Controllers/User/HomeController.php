<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Footer;
use App\Models\MainSeo;
use App\Models\Package;

class HomeController extends Controller
{
    public function index(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $categories = Category::where('status',1)->get();
         $countries = Country::where('status', 1)
        ->whereHas('packages', function ($query) {
            $query->where('status', 1);
        })
        ->with(['packages' => function ($query) {
            $query->where('status', 1);
        }])
        ->get();
        $packages = Package::with([
        'country:id,country_name',
        'type:id,type_name',
        'tag:id,tag_name'
        ])
        ->where('status',1)
        ->get()
        ->transform(function ($package) {
                $total = 0;
                $count = 0;
                foreach ($package->reviews as $review) {
                    foreach ($review->rating as $rating) {
                        $total += $rating->review_rating;
                        $count++;
                    }
                }
                $package->average_rating = number_format($count ? $total / $count : 0, 1);
                $package->rating_count = $count;
                return $package;
        }); 
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.index')->with(['categories'=>$categories,'packages'=>$packages,'follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'countries'=>$countries,'seo'=>$seo]);
    }
    public function about(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
         $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.about')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
}
