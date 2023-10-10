<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()-> role_as == 0){
            return view('layouts.admindash');
        }
        if(Auth::user()-> role_as == 2){
            // return view('layouts.studentdash');
            return view('student.studentpage.studentdashboardcontent');
        }
    }

    public function test(){
        // return view('admin.adminpage.adminallsubmission');
        return view('student.studentpage.studenthome');
    }

}
