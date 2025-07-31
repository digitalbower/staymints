<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Country;
use App\Models\Footer;
use App\Models\Header;
use App\Models\MainSeo;
use App\Models\Package;
use App\Models\Rating;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
        public function packages(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $headers = Header::where('status',1)->get();
        $packages = Package::with([
            'country:id,country_name',
            'type:id,type_name',
            'tag:id,tag_name',
            'reviews.rating'
            ])->where('status',1)->paginate(12);
            $countries = Country::where('status',1)->get();
            $categories = Category::where('status',1)->get();

            $packages->setCollection(
                $packages->getCollection()->transform(function ($package) {
                    $totalRating = 0;
                    $ratingCount = 0;

                    foreach ($package->reviews as $review) {
                        foreach ($review->rating as $rating) {
                            $totalRating += $rating->review_rating;
                            $ratingCount++;
                        }
                    }

                    $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;

                    $package->average_rating = number_format($averageRating, 1);
                    $package->rating_count = $ratingCount;

                    return $package;
                })
            );

            // ✅ Count number of packages in each star group (1–5)
            $ratingGroups = collect([
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
            ]);

            foreach ($packages as $package) {
                $avg = (float) $package->average_rating;

                if ($avg >= 1.0 && $avg < 2.0) {
                    $group = 1;
                } elseif ($avg >= 2.0 && $avg < 3.0) {
                    $group = 2;
                } elseif ($avg >= 3.0 && $avg < 4.0) {
                    $group = 3;
                } elseif ($avg >= 4.0 && $avg < 5.0) {
                    $group = 4;
                } elseif ($avg == 5.0) {
                    $group = 5;
                } else {
                    continue;
                }

                $current = $ratingGroups->get($group, 0);
                $ratingGroups->put($group, $current + 1);
            }

            $ratingCounts = $ratingGroups->toArray();


        // Get min and max starting price
        $minPrice = Package::min('starting_price');
        $maxPrice = Package::max('starting_price');

        // Number of ranges you want
        $steps = 5;

        // Calculate the interval size
        $interval = ceil(($maxPrice - $minPrice) / $steps);

        $priceRanges = [];

        for ($i = 0; $i < $steps; $i++) {
            $start = $minPrice + ($interval * $i);
            $end = ($i == $steps - 1) ? $maxPrice : ($start + $interval - 1);

            $priceRanges[] = [
                'label' => number_format($start) . ' - ' . number_format($end),
                'value' => $start . '-' . $end,
            ];
        }
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages,'countries'=>$countries,'categories'=>$categories,'priceRanges'=>$priceRanges,'ratingCounts' => $ratingCounts,'minPrice'=>$minPrice,'maxPrice'=>$maxPrice,'seo'=>$seo,'headers'=>$headers]);
    }
    public function packageSearch(Request $request){  
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $headers = Header::where('status',1)->get();
        $query = Package::with(['country','type','tag'])->where('status', 1);
        $countries = Country::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        // Get min and max starting price
        $minPrice = Package::min('starting_price');
        $maxPrice = Package::max('starting_price');

        // Number of ranges you want
        $steps = 5;

        // Calculate the interval size
        $interval = ceil(($maxPrice - $minPrice) / $steps);

        $priceRanges = [];

        for ($i = 0; $i < $steps; $i++) {
            $start = $minPrice + ($interval * $i);
            $end = ($i == $steps - 1) ? $maxPrice : ($start + $interval - 1);

            $priceRanges[] = [
                'label' => number_format($start) . ' - ' . number_format($end),
                'value' => $start . '-' . $end,
            ];
        }
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $packages = $query->paginate(12);
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages,'countries'=>$countries,'categories'=>$categories,'priceRanges'=>$priceRanges,'minPrice'=>$minPrice,'maxPrice'=>$maxPrice,'seo'=>$seo,'headers'=>$headers]);
    }
    public function filterSearch(Request $request)
    { 
        $request->validate([
            'country_id' => 'nullable|integer|exists:countries,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'duration' => 'nullable|string', 
        ]);
        $query = Package::with(['country:id,country_name', 'type:id,type_name', 'tag:id,tag_name', 'reviews.rating'])
                ->where('status', 1);
        
        if ($request->filled('country_id')) { 
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('duration')) {

            [$minNights, $maxNights] = explode('-', $request->duration); 

            // Convert nights to days by adding 1
            $minDays = (int)$minNights - 1; 
            $maxDays = (int)$maxNights - 1;

            // Filter on `duration` (in nights) between minDays-1 and maxDays-1
            $query->whereBetween('duration', [$minDays - 1, $maxDays - 1]);
        }
        if ($request->filled('price')) {
            $priceFilters = $request->price; // array of strings like "1000-10000"

            $query->where(function ($q) use ($priceFilters) {
                foreach ($priceFilters as $range) {
                    // Split the range into start and end values
                    list($start, $end) = explode('-', $range);

                    $q->orWhereBetween('starting_price', [(int)$start, (int)$end]);
                }
            });
        }
        

        $minPrice = (int) $request->input('min_price');
        $maxPrice = (int) $request->input('max_price');
        if ($minPrice != null && $maxPrice != null) {
            $query->whereBetween('starting_price', [$minPrice, $maxPrice]);
        }

            
        // Sorting on DB for duration and price (more efficient)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'duration_desc':
                    $query->orderBy('duration', 'desc');
                    break;
                case 'duration_asc':
                    $query->orderBy('duration', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('starting_price', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('starting_price', 'asc');
                    break;
                // rating sorting handled later
            }
        }

        // Fetch filtered & sorted packages from DB
        $allPackages = $query->get();

        
        // Calculate average_rating & rating_count for each package
        $enrichedPackages = $allPackages->map(function ($package) {
            $total = 0;
            $count = 0;
            foreach ($package->reviews as $review) {
                foreach ($review->rating as $rating) {
                    $total += $rating->review_rating;
                    $count++;
                }
            }
            $avg = $count ? $total / $count : 0;
            $package->average_rating = round($avg, 1);
            $package->rating_count = $count;
            return $package;
        });

        // Filter by rating range if requested (e.g. "2.0-3.0")
        if ($request->filled('rating')) {
            [$minRating, $maxRating] = explode('-', $request->rating);
            $minRating = (float)$minRating;
            $maxRating = (float)$maxRating;

            $enrichedPackages = $enrichedPackages->filter(function ($package) use ($minRating, $maxRating) {
                return $package->average_rating >= $minRating && $package->average_rating < $maxRating;
            });
        }

        // Sort by rating if requested
        if ($request->filled('sort')) {
            if ($request->sort === 'rating_desc') {
                $enrichedPackages = $enrichedPackages->sortByDesc('average_rating');
            } elseif ($request->sort === 'rating_asc') {
                $enrichedPackages = $enrichedPackages->sortBy('average_rating');
            }
        }

        // Pagination
        $page = $request->input('page', 1);
        $perPage = 12;
        $total = $enrichedPackages->count();
        $sliced = $enrichedPackages->slice(($page - 1) * $perPage, $perPage)->values();

        $packages = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => request()->query()]
        );

        // Calculate counts for rating filters on sidebar
        $ratingCounts = collect([1, 2, 3, 4, 5])->mapWithKeys(function ($star) use ($enrichedPackages) {
            $count = $enrichedPackages->filter(function ($package) use ($star) {
                return $package->average_rating >= $star && $package->average_rating < ($star + 1);
            })->count();

            return [$star => $count];
        })->toArray();

        $countries = Country::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        $follow_us = Footer::where('type', 'Follow On Us')->get();
        $partners = Footer::where('type', 'Payment Partners')->get();
        $links = Footer::where('type', 'Quick Links')->get();
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        // Get min and max starting price
        $minPrice = Package::min('starting_price');
        $maxPrice = Package::max('starting_price');
        $headers = Header::where('status',1)->get();
        // Number of ranges you want
        $steps = 5;

        // Calculate the interval size
        $interval = ceil(($maxPrice - $minPrice) / $steps);

        $priceRanges = [];

        for ($i = 0; $i < $steps; $i++) {
            $start = $minPrice + ($interval * $i);
            $end = ($i == $steps - 1) ? $maxPrice : ($start + $interval - 1);

            $priceRanges[] = [
                'label' => number_format($start) . ' - ' . number_format($end),
                'value' => $start . '-' . $end,
            ];
        }
        
        if ($request->ajax()) {
            return view('users.partials.package_list', compact('packages'))->render();
        }
        return view('users.packages', compact('packages', 'countries', 'categories', 'follow_us', 'partners', 'links','priceRanges','ratingCounts','minPrice','maxPrice','seo','headers'));
    }
    public function packageDetail($slug){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $currentPath = request()->path();  
        $slug = str_replace('package/', '', $currentPath);
        $slug = trim($slug, '/');
        $seo = Package::where('slug', $slug)->first();
        $headers = Header::where('status',1)->get();
        $package = Package::where('slug', $slug)->firstOrFail();
        $packages = Package::with('reviews.rating')
            ->where('recommendation', 1)
            ->where('id', '!=',  $package->id)
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
        'reviewsWithAverages'=>$reviewsWithAverages,'packages'=>$packages,'seo'=>$seo,'headers'=>$headers]);
    }
    public function booking(Request $request){  
        $validated = $request->validate([
            'booking_month' => 'required',
            'booking_year' => 'required',
            'adults_quantity' => 'required',
            'starting_price' => 'required',
        ]); 
        $requirements = [
            'requirement_type' => 'booking_details',
            'booking_month' => $request->booking_month,
            'booking_year' => $request->booking_year,
            'adults_quantity' => $request->adults_quantity,
            'children_quantity' =>$request->children_quantity,
            'infants_quantity' =>$request->infants_quantity,
            'services' => json_encode($request->services),
            'starting_price' => $request->starting_price,
        ];
        if (Auth::check()) {
            $fullName = Auth::user()->name;
            $parts = explode(' ', $fullName, 2);

            $requirements['user_id'] = Auth::id();
            $requirements['first_name'] = $parts[0];
            $requirements['last_name'] = $parts[1] ?? null;
            $requirements['email'] = Auth::user()->email; 
            $requirements['phone'] = Auth::user()->phone; 
        } else {
            $requirements['user_id'] = null;
            $requirements['first_name'] = null;
            $requirements['last_name'] = null;
            $requirements['email'] = null; 
            $requirements['phone'] = null; 
        }


        $booking = Booking::create([
            'requirements' => $requirements
        ]);

        $requirements['id'] = $booking->id;

        return response()->json([
            'message' => 'Booking successful',
            'data' => $requirements
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
        $data['user_id'] = Auth::check() ? Auth::id() : null;
        $booking = Booking::find($data['id']);
        $booking->update($data);

        return response()->json([
        'message' => 'Our holiday expert will get in touch with you.',
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
        $user = auth()->user();

        // Prevent duplicate entries
        if ($user->wishlists()->where('package_id', $package_id)->exists()) {
            return response()->json(['message' => 'Already in your wishlist.']);
        }

        $user->wishlists()->attach($package_id);

        return response()->json(['message' => 'Added to your wishlist!']);
    }
    
    public function getQuote(Request $request){
         $request->validate([
            'first_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'agree_terms' => 'required',
            'requirements' => 'required'
        ]);
        $data = $request->all(); 
        $data['user_id'] = Auth::check() ? Auth::id() : null;
        $data['requirements' ]= [
                'requirement_type' => 'note',
                'requirements' => $request->requirements
        ];
        
        Booking::create($data);
        return redirect()->back()->with('success', 'Our holiday expert will get in touch with you.');
    }
}
