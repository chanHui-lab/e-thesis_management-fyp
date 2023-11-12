<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Presentation_schedule;
use App\Models\SubmissionPost;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PresentationScheduleController extends Controller
{
    // public function index(Request $request)
    // {
    //     // if ($request->ajax()) {
    //     //     $events = Presentation_schedule::all(); // Assuming you have a Presentation model
    //     //     dd($events);
    //     //     return response()->json($events);
    //     // }

    //     // return view('admin.presentationSchedule');
    //     if ($request->ajax()) {
    //     $events = Presentation_schedule::all(); // Assuming you have a Presentation model
    //         // dd($events);
    //     return response()->json($events);}
    //     return view('admin.presentationSchedule');

    // }

// public function getEventds(Request $request)
// {
//     if ($request->ajax()) {
//         // Fetch events from the database
//         $events = Presentation_schedule::all(); // Modify this to fetch events as needed
//         dd($events);

//         // Format events as needed for FullCalendar
//         $formattedEvents = [];
//         foreach ($events as $event) {
//             $formattedEvents[] = [
//                 'title' => $event->title,
//                 'start' => $event->start->format('Y-m-d\TH:i:s'),
//                 'end' => $event->end->format('Y-m-d\TH:i:s'),
//                 // Add other event properties as needed
//             ];
//         }

//         return response()->json($formattedEvents);
//     }
//     return view('admin.presentationSchedule');

// }

public function getEvents(Request $request)
{
    // Fetch events from Presentation_schedule table
    $presentationEvents = Presentation_schedule::all(); // Assuming you have a Presentation model

    // Group events by their start date (you can adjust this as needed)
    // $eventsByDay = $presentations->groupBy(function ($event) {
    //     $carbonDate = Carbon::parse($event->start);
    //     return $carbonDate->format('Y-m-d');
    // });
    // return view('admin.presentationSchedule', compact('presentations','eventsByDay'));

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

    // $eventsByDay = $combinedEvents->groupBy(function ($event) {
    //     $carbonDate = Carbon::parse($event->start);
    //     return $carbonDate->format('Y-m-d');
    // });

    return view('admin.presentationSchedule', compact('combinedEvents'));

}

// public function editEvent($eventId) {
//     $presentations = Presentation_schedule::find($eventId); // Replace 'Event' with your actual model name
//     return view('edit_event', compact('presentations'));
// }


public function getEventDetails($eventId)
{
    // Use Eloquent or any other method to retrieve event details from the database
    $event = Presentation_schedule::find($eventId);

    if ($event) {
        return response()->json($event);
    }

    // Handle the case where the event doesn't exist or other errors
    return response()->json(['error' => 'Event not found'], 404);
}

// public function update(Request $request, $id)
// {
//     // Validate and update the event in the database
//     $event = Presentation_schedule::find($id);

//     $event->title = $request->input('title');
//     $event->description = $request->input('description');
//     $event->start = $request->input('start');
//     $event->end = $request->input('end');
//     $event->location = $request->input('location');

//     $event->save();

//     return redirect()->route('admin.presentationSchedule')->with('success', 'Event updated successfully');
// }

public function updateEvent(Request $request, $eventId)
{
    // Validate the request data as needed
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'string',
        'start' => 'required|date',
        // 'end' => 'date|nullable',
        'end' => 'required|date|after:start',
        'location' => 'required|string',

    ]);

    // Find the event in the database by ID
    $event = Presentation_schedule::find($eventId);

    if ($event) {
        // Update the event properties with the validated data
        $event->title = $validatedData['title'];
        $event->description = $validatedData['description'];
        $event->start = $validatedData['start'];
        $event->end = $validatedData['end'];
        $event->location = $validatedData['location'];

        // Save the changes
        $event->save();

        return redirect()->back()->with('success', 'Event updated successfully');
        // return response()->json($event);

    }

    // Handle the case where the event doesn't exist or other errors
    return redirect()->back()->with('error', 'Event not found or update failed');
}

public function fetchUpdatedEventSource() {
    $updatedEvents = Presentation_schedule::select('id', 'title', 'start', 'end', 'description', 'location')->get();

    return $updatedEvents;
}

    public function store(Request $request)
    {
        $event = new Presentation_schedule;
        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->start = $request->input('start');
        $event->end = $request->input('end');
        $event->location = $request->input('location');
        // You can add more fields here, including color (if you decide to save it)

        $event->save();

        return response()->json(['id' => $event->id]);
    }

    // public function getEvents()
    // {
    //     // Retrieve events from your database, adjust the query as needed
    //     $events = Presentation_schedule::all();

    //     // Group events by their start date (you can adjust this as needed)
    //     $eventsByDay = $events->groupBy(function ($event) {
    //         return $event->start->format('Y-m-d');
    //     });

    //     return view('your-view', compact('eventsByDay'));
    // }

    private function groupEventsByDay($events)
    {
        $groupedEvents = [];
        foreach ($events as $event) {
            $startDay = date('Y-m-d', strtotime($event->start));
            $groupedEvents[$startDay][] = $event;
        }
        return $groupedEvents;
    }

    // latest
    // public function getEventsByDate(Request $request)
    //     {
    //     $selectedDate = $request->input('date');

    //     // Fetch events for the selected date, assuming 'start' is the date field
    //     $events = Presentation_schedule::whereDate('start', $selectedDate)->get();

    //     return response()->json($events);
    // }
    // public function getDateEvents(Request $request, $date){
    //     // $events = Presentation_schedule::whereDate('start', $date)->get();

    //     //comment first
    //     // $formattedDateee = \Carbon\Carbon::parse($formattedDate)->format('Y-m-d');
    //     // \Log::info('Formatted Date: ' . $formattedDateee);

    //     // $date = \Carbon\Carbon::createFromFormat('D M d Y H:i:s eO (T)', $formattedDate);
    //     // $formattedDateee = $date->format('Y-m-d');

    //     // $formattedDate = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
    //     // $events = Presentation_schedule::where('start', 'LIKE', $formattedDate . '%')->get();

    //     // Query events for the specific date range (from midnight to the end of the day).
    //     $events = Presentation_schedule::where('start', '>=', $date . ' 00:00:00')
    //     ->where('start', '<=', $date . ' 23:59:59')
    //     ->get();
    //     dd($events);

    //     return response()->json($events);
    // }

    // public function getDateEvents(Request $request, $date)
    // {
    //     try {
    //         // Parse the input date to match the database format
    //         $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
    //         // dd($formattedDate);

    //         // Query the events with the formatted date
    //         // $events = Presentation_schedule::where('start', 'LIKE', $formattedDate . '%')->get();
    //         $presentation = Presentation_schedule::whereDate('start', '=', $formattedDate)->get();
    //         $forms = SubmissionPost::whereDate('start', '=', $formattedDate)->get();

    //         // Convert collections to arrays and concatenate
    //         $presentationArray = $presentation->toArray();
    //         $formsArray = $forms->toArray();
    //         $events = array_merge($presentationArray, $formsArray);

    //         return response()->json($events);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Internal Server erriririr'], 500);
    //     }
    // }

    public function getDateEvents(Request $request, $date)
    {
        try {
            // Parse the input date to match the database format
            $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');

            // Query the events with the formatted date
            // $presentation = Presentation_schedule::whereDate('start', '=', $formattedDate)->get();
            // $forms = SubmissionPost::whereDate('submission_deadline', '=', $formattedDate)->get();
            $presentation = Presentation_schedule::whereDate('start', '=', $formattedDate)
                ->get()
                ->map(function ($event) {
                    $event['type'] = 'presentation';
                    return $event;
            });

            $forms = SubmissionPost::whereDate('submission_deadline', '=', $formattedDate)
                ->get()
                ->map(function ($event) {
                    $event['type'] = 'forms';
                    return $event;
            });

        $events = $presentation->concat($forms);

        return response()->json($events);

            // Convert collections to arrays and concatenate
            $presentationArray = $presentation->toArray();
            $formsArray = $forms->toArray();
            $events = array_merge($presentationArray, $formsArray);

            return response()->json($events);
        } catch (\Exception $e) {
            // Log the error details
            \Log::error('Error in getDateEvents: ' . $e->getMessage());

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function changeEvent(Request $request, $id)
    {

        // Validate the request data as needed
    $validatedData = $request->validate([
        'title' => 'required|string',
        'description' => 'string',
        'start' => 'required|date',
        // 'end' => 'date|nullable',
        'end' => 'required|date|after:start',
        'location' => 'required|string',

    ]);

    // Find the event in the database by ID
    $event = Presentation_schedule::find($id);

    if ($event) {
        // Update the event properties with the validated data
        $event->title = $validatedData['title'];
        $event->description = $validatedData['description'];
        $event->start = $validatedData['start'];
        $event->end = $validatedData['end'];
        $event->location = $validatedData['location'];

        // Save the changes
        $event->save();

        return redirect()->back()->with('success', 'Event updated successfully');
        // return response()->json($event);

    }
    }

    }
