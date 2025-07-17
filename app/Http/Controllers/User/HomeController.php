<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Footer;
use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $categories = Category::where('status',1)->get();
        $countries = Country::where('status',1)->get();
        $packages = Package::with([
        'country:id,country_name',
        'type:id,type_name',
        'tag:id,tag_name'
        ])->where('status',1)->get(); 
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.index')->with(['categories'=>$categories,'packages'=>$packages,'follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'countries'=>$countries]);
    }
    public function about(){
         $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.about')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links]);
    }
    public function login(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.login')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links]);
    }
    public function preview(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.preview')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links]);
    }
    public function packages(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $packages = Package::with([
            'country:id,country_name',
            'type:id,type_name',
            'tag:id,tag_name'
            ])->where('status',1)->paginate(12);
            $countries = Country::where('status',1)->get();
            $categories = Category::where('status',1)->get();
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
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages,'countries'=>$countries,'categories'=>$categories,'priceRanges'=>$priceRanges]);
    }
    public function packageSearch(Request $request){  
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
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages,'countries'=>$countries,'categories'=>$categories,'priceRanges'=>$priceRanges]);
    }
    public function filterSearch(Request $request)
    { 
        $request->validate([
            'country_id' => 'nullable|integer|exists:countries,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'duration' => 'nullable|string', 
        ]);
        $query = Package::with(['country:id,country_name', 'type:id,type_name', 'tag:id,tag_name'])
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
        $packages = $query->paginate(12);

        if ($request->ajax()) {
            return view('users.partials.package_list', compact('packages'))->render();
        }

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
        return view('users.packages', compact('packages', 'countries', 'categories', 'follow_us', 'partners', 'links','priceRanges'));
    }

    public function packageDetail(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.package_detail')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links]);
    }
    public function contact(){
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.contact')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links]);
    }
}
