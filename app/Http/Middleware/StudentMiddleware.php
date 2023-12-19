<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!empty(Auth::check())){
            if(!Auth::user()->role_as == '2'){
                return $next($request);
            }
            else{
                dd(session('status'));
                Auth::logout();
                // return redirect('/home')->with('status','Access denied. You are not Student');
                return redirect('/')->with('status', 'Not Admin. Please log in to access this page.');

            }
        }
        // return redirect('/login')->with('status', 'Please log in to access this page.');

    }
}
