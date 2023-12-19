<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\DeadlineApproachingEvent;
use App\Notifications\DeadlineNotification;
use App\Console\Commands\LaravelLog;
use App\Models\User;
use Illuminate\Support\Facades\Notification;


class DeadlineApproachingListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    // public function handle(DeadlineApproachingEvent $event)
    // {
    //     // Log statement to check if the listener is being triggered
    //     \Log::info("Handling DeadlineApproachingEvent for submission {$event->submission->id}");

    //     $submission = $event->submission;
    //     // Ensure that the user is loaded before attempting to notify
    //     if ($submission->lecturer) {
    //         // Example: Send a deadline notification to the user
    //         $submission->lecturer->notify(new DeadlineNotification($submission));

    //     // // Example: Send a deadline notification to the user
    //     // $submission->user->notify(new DeadlineNotification($submission));
    //     \Log::info("{$submission}");

    //     }else {
    //         // Log a warning or handle the case where the user is not found
    //         \Log::warning("User not found for submission {$submission->id}");
    //     }
    // }


    // IF THERE IS SUBMISSION
    // public function handle(DeadlineApproachingEvent $event)
    // {
    //     // Log statement to check if the listener is being triggered
    //     \Log::info("Handling DeadlineApproachingEvent for submission {$event->submission->id}");

    //     $submission = $event->submission;

    //     // Check if there are form submissions related to this submission post
    //     if ($submission->formSubmissions()->count() > 0) {
    //         // At least one submission exists, so you can notify the student
    //         $student = $submission->formSubmissions->first()->student; // Assuming there is a 'student' relationship in the Form_submission model
    //         $student->notify(new DeadlineNotification($submission));
    //     } else {
    //         // Log a message or handle the case where no submissions exist
    //         \Log::info("No submissions found for submission {$submission->id}");
    //     }
    // }

    // public function handle(DeadlineApproachingEvent $event)
    // {
    //     // Log statement to check if the listener is being triggered
    //     \Log::info("Handling DeadlineApproachingEvent for submission {$event->submission->id}");

    //     $submission = $event->submission;

    //     // Check if there are no form submissions related to this submission post
    //     if ($submission->formSubmissions()->count() == 0) {
    //         // No submissions found, so you can notify the student
    //         $firstSubmission = $submission->formSubmissions()->first();

    //         if ($firstSubmission && $firstSubmission->student) {
    //             // Notify the student
    //             $student = $firstSubmission->student;
    //             $student->notify(new DeadlineNotification($submission));
    //         } else {
    //             // Log a message or handle the case where student or submission is null
    //             \Log::warning("Student or submission is null for submission {$submission->id}");
    //         }
    //     } else {
    //         // Log a message or handle the case where submissions exist
    //         \Log::info("Submissions found for submission {$submission->id}");
    //     }
    // }

    // public function handle(DeadlineApproachingEvent $event)
    // {
    //     // Log statement to check if the listener is being triggered
    //     \Log::info("Handling DeadlineApproachingEvent for submission {$event->submission->id}");

    //     $submission = $event->submission;

    //     // Check if there are any form submissions related to this submission post
    //     if ($submission->formSubmissions()->exists()) {
    //         // Get the first submission
    //         $firstSubmission = $submission->formSubmissions()->first();

    //         // Check if the first submission has a student
    //         if ($firstSubmission && $firstSubmission->student) {
    //             // Notify the student
    //             $student = $firstSubmission->student;
    //             $student->notify(new DeadlineNotification($submission));
    //         } else {
    //             // Log a message or handle the case where student or submission is null
    //             \Log::warning("Student or submission is null for submission {$submission->id}");
    //         }
    //     } else {
    //         // No form submissions found, notify the currently logged-in student
    //         $user = auth()->user();

    //         if ($user && $user->role_as == 2) { // Assuming role_as 2 represents a student
    //             $user->notify(new DeadlineNotification($submission));
    //         } else {
    //             // Log a message or handle the case where the user is not a student
    //             \Log::info("No form submissions found for submission {$submission->id}, and user is not a student");
    //         }
    //     }
    // }
    public function handle(DeadlineApproachingEvent $event)
    {
        $submission = $event->submission;

        // Fetch all students in the system
        $students = User::where('role_as', 2)->get(); // Assuming 'role_as' 2 represents students

        foreach ($students as $student) {
            // $student->notify(new DeadlineNotification($submission));
            $notification = new DeadlineNotification($submission);
            Notification::send($student, $notification);
            \Illuminate\Support\Facades\Log::info("After sending notification to student {$student->id}");

            \Illuminate\Support\Facades\Log::info("Notifying student {$student->id} about approaching deadline for submission {$submission->id}");
        }
    }

    }
