<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // protected function redirectTo()
    // {
    //     $roleAs = auth()->user()->role_as;

    //     switch ($roleAs) {
    //         case 0:
    //             return route('admin_dashboard');
    //         case 1:
    //             return route('lecturer_dashboard');
    //         case 2:
    //             return route('student_dashboard');
    //         default:
    //             return route('home');
    //     }
    // }

    // protected function redirectTo()
    // {
    //     if (auth()->user()->is_admin) {
    //         return route('admin_dashboard');
    //     } else {
    //         return route('student_dashboard');
    //     }
    // }

    protected function authenticated(){
        if(Auth::user()->role_as == '0'){
            return redirect('admin/dashboard')->with('status','Welcome');
        }
        else if(Auth::user()->role_as == '1'){
            return redirect('admin/dashboard')->with('status','Welcome teacher');
        }
        else if(Auth::user()->role_as == '2'){
            return redirect('student/dashboard')->with('status','Welcome student');
        }
        else{
            return redirect('/home')->with('status','Logged in Successfully');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
