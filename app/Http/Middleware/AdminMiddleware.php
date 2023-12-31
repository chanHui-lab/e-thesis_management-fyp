<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// must import this, if  not error: Auth not found
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(Auth::check())) {
            if (Auth::user()->role_as == '0') { // Check for numeric value 0
                return $next($request);
            } else {
                // Redirect or handle unauthorized access for non-admin users
                // dd(session('status'));
                return redirect('/')->with('status', 'Not Admin. Please log in to access this page.');
            }
        }

        // Redirect or handle unauthorized access for non-authenticated users
        return $next($request);
    }
}
