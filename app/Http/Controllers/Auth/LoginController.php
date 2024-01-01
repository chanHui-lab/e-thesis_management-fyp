<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    // protected function redirectTo()
    // {
    //     $roleAs = auth()->user()->role_as;
    //     dd(Auth::user()->role_as);
    //     switch ($roleAs) {
    //         case 0:
    //             return route('admin_dashboard');
    //         case 1:            dd(Auth::user()->role_as);

    //             return route('lecturer_dashboard');
    //         case 2:
    //             return route('student_dashboard');
    //         default:
    //             return route('home');
    //     }
    // }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
            'status' => 'Invalid credentials or role. Please try again.',
        ])->redirectTo('/');
    }

    protected function authenticated(Request $request, $user){

        \Log::info('User role_as: ' . $user->role_as);

        if($user->role_as == '0'){
            return redirect('admin/dashboard')->with('status','Welcome');
        }
        // else if(Auth::user()->role_as == 1){
        else if($user->role_as == '1'){

            // dd(Auth::user()->role_as);
            return redirect('lecturer/dashboard')->with('status','Welcome teacher');
        }

        else if(Auth::user()->role_as == '2'){
            return redirect('student/dashboard')->with('status','Welcome student');
        }
        else{
            // return redirect('/home')->with('status','Logged in Successfully');
            return redirect('admin/dashboard')->with('status','Welcome');

        }

        \Log::info('Default redirection');

    }
    protected function credentials(Request $request)
    {
        \Log::info('in credentials');
        return array_merge($request->only($this->username(), 'password'), ['role_as' => $request->input('role_as')]);
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
