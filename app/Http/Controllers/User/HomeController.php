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
        $packages = Package::with(['country','type','tag'])->where('status',1)->get(); 
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
        $packages = Package::with(['country','type','tag'])->where('status',1)->paginate(12);
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages]);
    }
    public function packageSearch(Request $request){  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $query = Package::with(['country','type','tag'])->where('status', 1);

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $packages = $query->paginate(12);
        return view('users.packages')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'packages'=>$packages]);
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
