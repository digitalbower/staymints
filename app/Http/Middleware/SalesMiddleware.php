<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SalesMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->user_role_id === 4) {
            return $next($request);
        }

        abort(403);
    }
}
