<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'section',
        'file_name',
        'file_data',
        'mime_type',
        'description',
        'status',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public static function getLecturerFormTemplates()
    {
        $loggedInUser = Auth::user(); // Assuming you are using Laravel's default authentication

        return self::where('section', 'form')
            ->where(function ($query) use ($loggedInUser) {
                $query->where('lecturer_id', $loggedInUser->id) // Templates uploaded by the lecturer
                    ->orWhereHas('lecturer', function ($query) use ($loggedInUser) {
                        $query->where('role_as', 0); // Admin role
                    });
            })
            ->select('templates.*')
            ->paginate(5);
    }

    static public function getLectFormTemplate(){
        return self::where('section', 'form')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('templates.*')
        // ->get();
        ->paginate(5);
    }

    static public function getAdminThesisTemplate(){
        return self::where('section', 'thesis')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('templates.*')
        // ->get();
        ->paginate(5);
    }

    // static public function getStuFormTemplate(){
    //     $lecturer = Auth::user()->supervisor;
    //     return self::where('section', 'form')
    //     ->where(function ($query) use ($lecturer) {
    //         $query->where('lecturer_id', $lecturer->id)
    //               ->orWhereHas('supervisor', function ($query) use ($lecturer) {
    //                   $query->where('lecturer_id', $lecturer->id);
    //               })
    //               ->orWhere('role_as', 0);
    //     })
    //     ->select('templates.*')
    //     ->paginate(2);
    // }
    static public function getStuFormTemplate(){
        $user = Auth::user();

        return DB::table('templates')
            ->join('users', 'users.id', '=', 'templates.lecturer_id')
            ->leftJoin('supervisors', 'supervisors.lecturer_id', '=', 'users.id')
            ->where(function ($query) use ($user) {
                $query->where('users.role_as', 0)
                      ->orWhere('supervisors.lecturer_id', '=', $user->supervisor_id);
            })
            ->where('templates.section', 'form')  // Added condition for the 'section' column
            ->get();
    }

    static public function getSingle($id){
        return self::find($id);
    }

    static public function getCalendarTemplate(){
        return self::where('section', 'calendarsche')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('templates.*')
        ->get();
    }

    static public function getProposalTemplate(){
        return self::where('section', 'proposal')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('templates.*')
        ->paginate(5);
    }

    static public function getSlideTemplate(){
        return self::where('section', 'slide')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('templates.*')
        ->paginate(5);
    }
    static public function getStuThesisTemplate(){
        $user = Auth::user();

        return DB::table('templates')
            ->join('users', 'users.id', '=', 'templates.lecturer_id')
            ->leftJoin('supervisors', 'supervisors.lecturer_id', '=', 'users.id')
            ->where(function ($query) use ($user) {
                $query->where('users.role_as', 0)
                      ->orWhere('supervisors.lecturer_id', '=', $user->supervisor_id);
            })
            ->where('templates.section', 'thesis')  // Added condition for the 'section' column
            ->paginate(5);
    }

    static public function getStuProposalTemplate(){
        $user = Auth::user();

        return DB::table('templates')
            ->join('users', 'users.id', '=', 'templates.lecturer_id')
            ->leftJoin('supervisors', 'supervisors.lecturer_id', '=', 'users.id')
            ->where(function ($query) use ($user) {
                $query->where('users.role_as', 0)
                        ->orWhere('supervisors.lecturer_id', '=', $user->supervisor_id);
            })
            ->where('templates.section', 'proposal')  // Added condition for the 'section' column
            ->paginate(5);
    }

    static public function getStuSlideTemplate(){
        $user = Auth::user();

        return DB::table('templates')
            ->join('users', 'users.id', '=', 'templates.lecturer_id')
            ->leftJoin('supervisors', 'supervisors.lecturer_id', '=', 'users.id')
            ->where(function ($query) use ($user) {
                $query->where('users.role_as', 0)
                        ->orWhere('supervisors.lecturer_id', '=', $user->supervisor_id);
            })
            ->where('templates.section', 'slide')  // Added condition for the 'section' column
            ->paginate(5);
    }
}
