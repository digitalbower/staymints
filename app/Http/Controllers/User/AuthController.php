<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Footer;
use App\Models\MainSeo;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        return view('users.login')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo]);
    }
  
    public function generateOtp(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'agree_terms' => 'nullable|boolean',
        ]);
        $data = $request->all();
        $otp = rand(100000, 999999);
        $data['otp'] =   Hash::make($otp);
        $data['expires_at'] = Carbon::now()->addMinutes(10);

        Session::put('register_temp', [
            'name' => $data['name'],
            'email' => $data['email'],
            'otp' => $data['otp'],
            'expires_at' => $data['expires_at'],
            'marketing' => $data['marketing'] ?? 0,
            'agree_terms' => $data['agree_terms'] ?? 0,
       ]);
        UserOtp::create($data);

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['success'=>true,'message' => 'OTP sent to your email']);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $temp = Session::get('register_temp');

        if (!$temp || !isset($temp['name'], $temp['email'], $temp['otp'], $temp['expires_at'])) {
            return redirect('/register')->withErrors(['error' => 'Session expired. Please register again.']);
        }

        if (now()->gt($temp['expires_at'])) {
            Session::forget('register_temp');
            return redirect('/register')->withErrors(['error' => 'OTP expired. Please register again.']);
        }

        if (!Hash::check($request->otp, $temp['otp'])) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $user = User::create([
            'name' => $temp['name'],
            'email' => $temp['email'],
            'otp' => $temp['otp'],
            'marketing' => $temp['marketing'] ?? 0,
            'agree_terms'=>$temp['agree_terms'] ?? 0,
        ]);

        Session::forget('register_temp');
        Auth::login($user);
        $redirectUrl = $request->input('redirect', route('home.index'));
        return redirect()->to($redirectUrl);
    }
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/login');
    }
    public function loginGenerateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate OTP
        $data = $request->all();
        $otp = rand(100000, 999999);
        $data['otp'] =   Hash::make($otp);
        $data['expires_at'] = Carbon::now()->addMinutes(10);

        Session::put('login_otp', [
            'email' => $data['email'],
            'otp' => $data['otp'],
            'expires_at' => $data['expires_at'],
       ]);
        UserOtp::create($data);

        Mail::to($request->email)->send(new OtpMail($otp));
        return response()->json(['success'=>true,'message' => 'OTP sent to your email']);
    }
    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionOtp = Session::get('login_otp');

        if (!$sessionOtp || now()->gt($sessionOtp['expires_at'])) {
            Session::forget('login_otp');
            return redirect()->route('login')->withErrors(['otp' => 'OTP expired. Try again.']);
        }

        if (!Hash::check($request->otp, $sessionOtp['otp'])) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // OTP is valid → log user in
        $user = User::where('email', $sessionOtp['email'])->first();
        Auth::login($user);

        Session::forget('login_otp');

        $redirectUrl = $request->input('redirect', route('home.index'));
        return redirect()->to($redirectUrl);
    }
      public function preview(){
        $currentPath = request()->path();
        $seo = MainSeo::where('page_url', $currentPath)->first()
        ?? MainSeo::where('page_url', 'default')->first();  
        $follow_us = Footer::where('type','Follow On Us')->get();
        $partners = Footer::where('type','Payment Partners')->get();
        $links = Footer::where('type','Quick Links')->get();
        $user = Auth::user();
        return view('users.preview')->with(['follow_us'=>$follow_us,'partners'=>$partners,'links'=>$links,'seo'=>$seo,'user'=>$user]);
    }
    public function previewSubmit(Request $request)
    {
        $request->validate([
           'phone' => 'required',
           'agree_terms' => 'nullable|boolean',

        ]);
        $data = $request->all();
        $user = User::where('id', $data['user_id'])->first(); 
        $user->update([
            'phone' => $data['phone'],
            'marketing' => $data['marketing'] ?? 0,
            'agree_terms'=>$data['agree_terms'] ?? 0,
        ]);
        Auth::login($user);
        return redirect()->route('home.index');
    }
}
