<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\Footer;
use App\Models\MainSeo;
use Illuminate\Http\Request;

class ContactController extends Controller
{
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
    public function contactSubmit(Request $request){
          $data = $request->validate([
            'name' => 'required',
            'email' =>'required',
            'phone' =>'required',
            'message'=>'required',
        ]);
        ContactUs::create($data);

        return redirect()->route('home.contact')->with('success', 'Message send successfully');
    }
}
