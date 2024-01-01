<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal_submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'submission_post_id',
        'proposal_title',
        'proposal_description',
        'student_id',
        'supervisor_id',
        'proposal_file',
        'proposal_type',
        'sem_id'
    ];

    // Thesis Submission.php
    static public function getSingle($id){
        return self::find($id);
    }

    public function submissionPost()
    {
        return $this->belongsTo(SubmissionPost::class, 'submission_post_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'stu_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'lecturer_id');
    }

    public function getTimeRemainingOrElapsed()
    {
        // Get the current date and time
        $now = Carbon::now();

        // Get the submission deadline date and time from the submission_post
        $submissionDeadline = $this->submissionPost->submission_deadline;

        // Check if there is a file attached to the submission
        if (!empty($this->form_files)) {
            $files = json_decode($this->form_files, true);

            // Check if there are files and if the last modified date is defined
            if (!empty($files) && isset($files[0]['uploaded_at'])) {
                $lastModified = Carbon::parse($files[0]['uploaded_at']);
                dd($lastModified);

                // Check if the last modified date is before the submission deadline
                if ($lastModified <= $submissionDeadline) {
                    $diff = $submissionDeadline->diff($lastModified);
                    return "Assignment was submitted " . $diff->format('%a days, %h hours, %i minutes') . " earlier";
                }
            }
        }

        // Calculate the time difference between the current time and the submission deadline
        $diff = $now->diff($submissionDeadline);

        // Determine if the deadline is in the future (time remaining) or in the past (time elapsed)
        if ($now < $submissionDeadline) {
            return "Time Remaining: " . $diff->format('%a days, %h hours, %i minutes');
        } else {
            return "Submission is overdue by " . $diff->format('%a days, %h hours, %i minutes');
        }
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
