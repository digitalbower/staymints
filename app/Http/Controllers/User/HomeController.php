<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Footer;
use App\Models\MainSeo;
use App\Models\Package;
use Illuminate\Http\Request;

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
    public function login(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.login')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
    public function preview(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.preview')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
    public function packages(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
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
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages,'countries'=>$countries,'categories'=>$categories,'priceRanges'=>$priceRanges,'ratingCounts' => $ratingCounts,'minPrice'=>$minPrice,'maxPrice'=>$maxPrice,'seo'=>$seo]);
    }
    public function packageSearch(Request $request){  
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
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
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages,'countries'=>$countries,'categories'=>$categories,'priceRanges'=>$priceRanges,'minPrice'=>$minPrice,'maxPrice'=>$maxPrice,'seo'=>$seo]);
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
        
        if ($request->ajax()) {
            return view('users.partials.package_list', compact('packages'))->render();
        }
        return view('users.packages', compact('packages', 'countries', 'categories', 'follow_us', 'partners', 'links','priceRanges','ratingCounts','minPrice','maxPrice'));
    }

    
    public function contact(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.contact')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
    public function termsAndCondition(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.terms')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
    public function privacyPolicy(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.privacy')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
}
