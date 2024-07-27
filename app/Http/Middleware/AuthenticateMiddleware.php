<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // Cần phải đăng nhập để sử dụng các chức năng của trang web 
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::id() == null) {
            toastr()->error("Bạn phải đăng nhập để sử dụng.");
            return redirect()->route('auth.admin');
        }
        return $next($request);
    }
}
