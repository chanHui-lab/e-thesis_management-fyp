<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, string ...$guards): Response
    // {
    //     $guards = empty($guards) ? [null] : $guards;

    //     foreach ($guards as $guard) {
    //         if (Auth::guard($guard)->check()) {
    //             return redirect(RouteServiceProvider::HOME);
    //             // return redirect()->route('admin_dashboard'); // Redirect to the 'dashboard' route.
    //         }
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Check for the role directly
                $user = Auth::guard($guard)->user();

                if ($user->role_as === 0) {
                    return redirect()->route('admin_dashboard');
                }
                elseif ($user->role_as === '1') {
                    return redirect()->route('lect_dashboard');
                }
                elseif ($user->role_as === '2') {
                    return redirect()->route('student_dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
