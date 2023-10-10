<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

// reminder
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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


    // get the current post from current id
    public function getStudentSubmissionPost()
    {
        $user = Auth::user(); // Get the current logged-in user
        $submissions = Submission::where('student_id', $user->id)
            ->where('section', 'form') // Replace 'section' with your actual column name
            ->get();

        return view('submissions.index', ['submissions' => $submissions]);
    }

    static public function getSingle($id){
        return self::find($id);

        // $getRecord = self::find($id);
        // $existingFiles = json_decode($getRecord->files, true) ?? [];

        // return compact('getRecord', 'existingFiles');
    }

//     static public function getSingle($id)
// {
//     $record = self::find($id);

//     // Fetch existing files associated with the record
//     $existingFiles = json_decode($record->files, true);

//     return [
//         'record' => $record,
//         'existingFiles' => $existingFiles,
//     ];
// }



}
