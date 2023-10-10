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
        if(!empty(Auth::check())){
            if(!Auth::user()->role_as == '0'){
                return $next($request);
            }
            else{
                // Auth::logout();
                return redirect('/home')->with('status','Access denied. You not Admin');
            }
        }
    }
}
