<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    public function showLoginForm(){  
        return view('admin.auth.login');
    }
        public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember'); 
        if (Auth::guard('admin')->attempt($credentials, $remember)) { 
            $request->session()->regenerate();
            if ($request->remember) {
                Cookie::queue('remember_admin', $request->remember ? '1' : '0', 43200); // Store remember token
                Cookie::queue('remember_email', $request->email, 43200);
                Cookie::queue('remember_password', $request->password, 43200);
            } else {
                Cookie::queue(Cookie::forget('remember_admin'));
                Cookie::queue(Cookie::forget('remember_email'));
                Cookie::queue(Cookie::forget('remember_password'));
            }
            $admin = Auth::guard('admin')->user();
            $adminId = $admin->id;
            $sessionId = session()->getId();
            $payload = serialize(session()->all());
            DB::table('sessions')->updateOrInsert(
                ['id' => $sessionId],
                [
                    'admin_id' => $adminId,  
                    'ip_address' => $request->ip(), 
                    'user_agent' => $request->userAgent(), 
                    'payload' => $payload, 
                    'last_activity' => now()->timestamp, 
                ]
            );
           
            $admin = Auth::guard('admin')->user();
            if ($admin->user_role_id === 1 || $admin->user_role_id === 2) { 
                return redirect()->route('admin.index');
            } elseif ($admin->user_role_id === 4) {
                return redirect()->route('admin.sales.leads');
            }
                        
            // Fallback for unexpected roles
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->withErrors(['role' => 'Unauthorized role']);
        }

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return back()->withErrors(['email' => 'This email is not registered.'])->withInput();
        }
        return back()->withErrors(['password' => 'The password you entered is incorrect.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/admin/login');
    }

}
