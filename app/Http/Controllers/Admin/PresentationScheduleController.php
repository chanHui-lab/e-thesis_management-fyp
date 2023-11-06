<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Presentation_schedule;

use Illuminate\Http\Request;

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
    $presentations = Presentation_schedule::all(); // Assuming you have a Presentation model

    return view('admin.presentationSchedule', compact('presentations'));

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
        'end' => 'date|nullable',
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


}
