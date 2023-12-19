<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// reminder
use Carbon\Carbon;

class SubmissionPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'section',
        'title',
        'description',
        'files',
        'submission_deadline',
        'visibility_status',
    ];


    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    static public function getAdminFormSP(){
        return self::where('section', 'form')
        ->whereHas('lecturer', function ($query) {
            $query->where('role_as', 0) // Admin role
                  ->orWhere('role_as', 1); // Lecturer role
        })
        ->select('submission_posts.*')
        // ->get();
        ->paginate(5);
    }

    static public function getStuFormSP(){
        $user = Auth::user();

        return DB::table('submission_posts')
            ->join('users', 'users.id', '=', 'submission_posts.lecturer_id')
            ->leftJoin('supervisors', 'supervisors.lecturer_id', '=', 'users.id')
            ->where(function ($query) use ($user) {
                $query->where('users.role_as', 0)
                      ->orWhere('supervisors.lecturer_id', '=', $user->supervisor_id);
            })
            ->where('section', 'form')
            ->get();
    }
    // // get the current post from current id -  not yet do.
    // public function getStudentSubmissionPost()
    // {
    //     $user = Auth::user(); // Get the current logged-in user
    //     $submissions = Submission::where('student_id', $user->id)
    //         ->where('section', 'form') // Replace 'section' with your actual column name
    //         ->get();

    //     return view('submissions.index', ['submissions' => $submissions]);
    // }

    static public function getSingle($id){
        return self::find($id);

        // $getRecord = self::find($id);
        // $existingFiles = json_decode($getRecord->files, true) ?? [];

        // return compact('getRecord', 'existingFiles');
    }

    public function formSubmissions()
    {
        return $this->hasMany(Form_submission::class, 'submission_post_id');
    }

    //to display certain student post!
    // public function supervisedStudents()
    // {
    //     // Assuming you have a 'supervisor_id' foreign key in the 'students' table
    //     return Student::where('supervisor_id', $this->lecturer_id)->get();
    // }

}
