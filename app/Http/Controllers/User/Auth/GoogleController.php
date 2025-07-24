<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $action = request()->get('action', 'login');
        session(['social_login_action' => $action]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            $action = session('social_login_action', 'login'); // default is login

            $user = User::where('email', $email)->first();

            // 🔒 Block login if user not found
            if (!$user && $action === 'login') {
                return redirect('/login')->with('error', 'Email not registered. Please sign up first.');
            }

            // 🔒 Block Google login if email exists but not registered via Google
            if ($user && !$user->google_id && $action === 'login') {
                return redirect('/login')->with('error', 'This email is registered with OTP login. Please use the OTP login method.');
            }

            // ✋ Block signup if user already exists
            if ($user && $action === 'signup') {
                return redirect('/login')->with('error', 'User already exists. Please log in.');
            }

            // ✅ Register new Google user (signup)
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                ]);
            }

            // ✅ Log in user
            Auth::login($user);

            // 🔁 Redirect based on original intent
            return redirect()->route($action === 'login' ? 'home.index' : 'home.preview');

        } catch (\Exception $e) {
            // Log::error($e); // for debugging
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
        }
    }

}
