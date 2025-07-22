<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::with(['package', 'rating'])->latest()->get()->transform(function ($review) {
            $total = 0;
            $count = 0;

            foreach ($review->rating as $rating) {
                $total += $rating->review_rating;
                $count++;
            }

            $review->average_rating = number_format($count ? $total / $count : 0, 1);
            $review->rating_count = $count;

            return $review;
        }); 

        return view('admin.review.index')->with(['reviews'=>$reviews]);
    }
    public function adminReply(Request $request){  
        $review = Review::find($request->review_id);
        $review->admin_reply = $request->admin_reply;
        $review->save();
        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Added reply successfully.');
    }
    public function deleteReview($id)
    {
        $review = Review::find($id);
        $review->rating()->delete();
        $review->delete();
        
        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Review deleted successfully.');
    }
}
