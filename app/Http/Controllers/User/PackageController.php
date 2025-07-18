<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingInfo;
use App\Models\Footer;
use App\Models\Package;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function packageDetail($id){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $package = Package::find($id);
        $packages = Package::where('recommendation', 1)
            ->where('id', '!=', $id)
            ->get();
        $gallery = json_decode($package->gallery, true);
        $slideShow = json_decode($package->slide_show, true);
        $coverImage = $slideShow[0] ?? null;  
        $include_infos = $package->included ?? null;  
        $exclude_infos = $package->excluded ?? null;
        $services = $package->extra_services ?? null;
        $itineraries = $package->itineraries ?? null;
        $reviews = $package->reviews ?? null;
        $reviewIds = $reviews->pluck('id');
        $ratings = Rating::whereIn('review_id', $reviewIds)->get();
        $averagePerReview = $ratings
            ->groupBy('review_id')
            ->map(function ($group) {
                return number_format($group->avg('review_rating'), 1);
            });
        $reviewsWithAverages = $reviews->map(function ($review) use ($averagePerReview) {
            $review->average_rating = $averagePerReview[$review->id] ?? '0.0';
            return $review;
        });
        $reviewCount = $package->reviews()->count();
        $ratings = Rating::whereIn('review_id', $package->reviews->pluck('id'))->get();
        $totalRatings = $ratings->sum('review_rating'); 
        $totalRatingsCount = $ratings->count();           

        $averageRating = $totalRatingsCount > 0 ? $totalRatings / $totalRatingsCount : 0;
        $averageRating = number_format($averageRating, 1);  

        // Count how many ratings of each score exist (1 to 5)
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = $ratings->where('review_rating', $i)->count();
        } 

        // Calculate percentages
        $percentages = [];
        foreach ($ratingCounts as $rating => $count) {
            $percentages[$rating] = $totalRatingsCount > 0 ? ($count / $totalRatingsCount) * 100 : 0;
        } 
        $averagePerCategory = $ratings
        ->groupBy('review_type')
        ->map(function ($group) {
            return number_format($group->avg('review_rating'), 1);
        });


        return view('users.package_detail')->with(['follow_us'=>$follow_us,'partners'=>$partners,
        'links'=>$links,'package'=>$package,'gallery'=>$gallery,'include_infos'=>$include_infos,
        'exclude_infos'=>$exclude_infos,'slideShow'=>$slideShow,'itineraries'=>$itineraries,
        'coverImage'=>$coverImage,'services'=>$services,'averageRating' => $averageRating,
        'reviewCount' => $reviewCount,'percentages' => $percentages,'averagePerCategory' => $averagePerCategory,
        'reviewsWithAverages'=>$reviewsWithAverages,'packages'=>$packages]);
    }
    public function booking(Request $request) {  
        $validated = $request->validate([
            'booking_month' => 'required',
            'booking_year' => 'required',
            'adults_quantity' => 'required',
            'starting_price' => 'required',
            'services' => 'required|array',
        ]); 
        $data = $request->all();
        $data['services'] = json_encode($request->services);


        $booking = Booking::create($data);

        return response()->json([
        'message' => 'Booking successful',
        'data' => $booking
    ]);

    }
    public function bookingConfirmation(Request $request) { 
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]); 
        $data = $request->all();

        $booking = Booking::find($data['id']);
        $booking->update($data);

        return response()->json([
        'message' => 'Booking successful',
        ]);
    
    }
    public function review(Request $request){ 
         $request->validate([
            'reviewer_name' => 'required',
            'reviewer_email' => 'required|email',
            'review_description' => 'required'
        ]);
        $data = $request->all(); 
        $review = Review::create($data); 
        foreach($request->ratings as $rating){  
            $new_rating = new Rating();  
            $new_rating->review_id = $review->id;
            $new_rating->review_type = $rating['review_type'];
            $new_rating->review_rating = $rating['review_rating'];
            $new_rating->save();
        }
        return response()->json([
        'message' => 'Review added successfully',
        ]);
    }
    public function wishlist($package_id){
        Wishlist::create(['package_id'=>$package_id]); 
        return response()->json([
        'message' => 'Added to wishlist',
        ]);
    }
}
