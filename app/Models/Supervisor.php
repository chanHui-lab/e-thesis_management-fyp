<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $primaryKey = 'lecturer_id'; // Specify the custom primary key column name

    protected $fillable = ['lecturer_id', 'department_name'];

    public function user()
    {
        return $this->belongsTo(User::class, "lecturer_id");
    }

    // User.php (or Lecturer.php)

    public function student()
    {
        // return $this->hasOne(Student::class, 'supervisor_id'); //laravel defaults
        // it to be reference to "id"

        return $this->hasMany(Student::class, 'supervisor_id', 'lecturer_id');

    }

    public function supervisedStudents()
    {
        return $this->hasMany(Student::class, 'supervisor_id', 'lecturer_id');
    }

    // Lecturer.php
    public function formSubmissions()
    {
        return $this->hasManyThrough(FormSubmission::class, Student::class, 'supervisor_id', 'stu_id')
            ->where('form_submissions.submission_post_id', $submissionPost->id);
    }

    public function thesisSubmissions()
    {
        return $this->hasManyThrough(Thesis_Submission::class, Student::class, 'supervisor_id', 'stu_id')
            ->where('thesis_submissions.submission_post_id', $submissionPost->id);
    }

    public function proposalSubmissions()
    {
        return $this->hasManyThrough(Proposal_Submission::class, Student::class, 'supervisor_id', 'stu_id')
            ->where('proposal_submissions.submission_post_id', $submissionPost->id);
    }
}
