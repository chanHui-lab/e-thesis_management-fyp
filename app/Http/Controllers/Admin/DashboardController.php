<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log as LaravelLog; // Import the Log facade with an alias

use App\Models\SubmissionPost;
use App\Models\Presentation_schedule;
use App\Models\Template;


class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()-> role_as == 0){
            return view('layouts.admindash');
        }
        if(Auth::user()-> role_as == 2){
            // return view('layouts.studentdash');
            $deadlines = DB::table('submission_posts')
                    ->whereNotNull('submission_deadline')
                    ->whereBetween('submission_deadline', [Carbon::now(), Carbon::now()->addWeeks(3)])
                    ->where('submission_deadline', '>=', Carbon::now())
                    ->get();

                            // Fetch events from Presentation_schedule table
        $presentationEvents = Presentation_schedule::all(); // Assuming you have a Presentation model
        $calen = Template::getCalendarTemplate();

        $student = auth()->user(); // Assuming you have a user() function to get the logged-in user
        $supervisorId = $student->supervisor_id; // Adjust this according to your actual relationship

        $thesisEvents = SubmissionPost::where(function ($query) use ($supervisorId) {
                $query->where('lecturer_id', $supervisorId) // Assuming supervisor_id is the field linking to the lecturer
                    ->orWhere(function ($adminQuery) {
                        // Fetch events created by admin (role_as = 0)
                        $adminQuery->whereHas('lecturer', function ($lecturerQuery) {
                            $lecturerQuery->where('role_as', 0);
                        });
                    });
            })
            ->get();

        // Fetch events from Thesis table
        $thesisEvents = SubmissionPost::all();

        $presentationEvents = $presentationEvents->map(function ($event) {
            return [
                'type' => 'presentation',
                'id' => $event->id, // Include the 'id' field
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start,
                'end' => $event->end,
                'location' => $event->location,
            ];
        });

        $thesisEvents = $thesisEvents->map(function ($event) {
            return [
                'type' => 'forms',
                'id' => $event->id, // Include the 'id' field
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->submission_deadline, // Assuming submission_deadline is the field
            ];
        });

        // Combine both sets of events
        $combinedEvents = $presentationEvents->merge($thesisEvents);

        return view('student.studentpage.studentdashboardcontent',['deadlines' => $deadlines], compact('combinedEvents','calen'));
        }
    }

    public function test(){
        // return view('admin.adminpage.adminallsubmission');
        return view('student.studentpage.studenthome');
    }

    public function fetchReminderData()
    {
        $b4data = SubmissionPost::select('id', 'title', 'section', 'submission_deadline')
        ->get();

        // Convert the existing submission_deadline values to the new timezone
        foreach ($b4data as $item) {
            $item->submission_deadline = Carbon::parse($item->submission_deadline)
                ->setTimezone('Asia/Kuala_Lumpur')
                ->toDateTimeString();

            // Add a formatted version of the deadline with time and day of the week
            $item->formatted_deadline = Carbon::parse($item->submission_deadline)
            ->format('l, d-M-Y h:i A');

            // Add a property indicating whether the deadline is today
            $item->is_today_deadline = Carbon::parse($item->submission_deadline)->isToday();

            // Calculate remaining days and hours
            $now = Carbon::now();
            $deadline = Carbon::parse($item->submission_deadline);
            $remainingTime = $now->diff($deadline);
            $item->remainingDays = $remainingTime->days;
            $item->remainingHours = $remainingTime->h;
            $item->remaining_minutes = $remainingTime->i;

        }
        $now = Carbon::now();
        $twoWeeksFromNow = $now->copy()->addWeeks(2);

        LaravelLog::info('Current Date: ' . Carbon::now());
        LaravelLog::info('Two Weeks from Now: ' . $twoWeeksFromNow);
        // $data = SubmissionPost::select('id', 'title', 'section', 'submission_deadline')
        //     ->where('submission_deadline', '<=', $twoWeeksFromNow)
        //     ->get();
        //     LaravelLog::info('Fetched Data: ' . json_encode($data));

        $data = $b4data->filter(function ($item) use ($now, $twoWeeksFromNow) {
            $submissionDeadline = Carbon::parse($item->submission_deadline);

            return $submissionDeadline >= $now && $submissionDeadline <= $twoWeeksFromNow;
        });

        LaravelLog::info('Fetched Data: ' . json_encode($data));

        return response()->json($data);
    }

    public function getStuEventsInDash(Request $request){

        // Fetch events from Presentation_schedule table
        $presentationEvents = Presentation_schedule::all(); // Assuming you have a Presentation model
        $calen = Template::getCalendarTemplate();

        $student = auth()->user(); // Assuming you have a user() function to get the logged-in user
        $supervisorId = $student->supervisor_id; // Adjust this according to your actual relationship

        $thesisEvents = SubmissionPost::where(function ($query) use ($supervisorId) {
                $query->where('lecturer_id', $supervisorId) // Assuming supervisor_id is the field linking to the lecturer
                    ->orWhere(function ($adminQuery) {
                        // Fetch events created by admin (role_as = 0)
                        $adminQuery->whereHas('lecturer', function ($lecturerQuery) {
                            $lecturerQuery->where('role_as', 0);
                        });
                    });
            })
            ->get();

        // Fetch events from Thesis table
        $thesisEvents = SubmissionPost::all();

        $presentationEvents = $presentationEvents->map(function ($event) {
            return [
                'type' => 'presentation',
                'id' => $event->id, // Include the 'id' field
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start,
                'end' => $event->end,
                'location' => $event->location,
            ];
        });

        $thesisEvents = $thesisEvents->map(function ($event) {
            return [
                'type' => 'forms',
                'id' => $event->id, // Include the 'id' field
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->submission_deadline, // Assuming submission_deadline is the field
            ];
        });

        // Combine both sets of events
        $combinedEvents = $presentationEvents->merge($thesisEvents);

        return view('student.studentpage.studentdashboardcontent', compact('combinedEvents','calen'));
    }

}
