<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Models\Header;
use App\Models\MainSeo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first(); 
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $headers = Header::where('status',1)->get();
        /** @var User $user */
        $user = Auth::user();
        $wishlistedPackages = $user->wishlists()->with('reviews.rating')->get()->transform(function ($package) {
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
        $package->in_wishlist = true; // always true since these are wishlisted

        return $package;
    });

    return view('users.profile')->with(['seo'=>$seo,'follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'user'=>$user,'packages'=>$wishlistedPackages,'headers'=>$headers]);
    }
}
