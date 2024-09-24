<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventAttendeeController extends Controller
{
    //
    public function index()
    {
        $events= Event::all();

        return view('admin.eventAttendees.index', compact('events'));
    }

    public function fetchEventDetails(Request $request)
    {
        $events= Event::all();

        $eventId = $request->input('eventId');

        $data = Event::where('id', $eventId)->first();

        $attendeesCount = $data->attendees->count();

        $attendees = $data->attendees()->paginate(15);

        $attendees->withPath(route('admin.event.details', ['eventId' => $eventId]));

        return view('admin.eventAttendees.index', compact('events','data', 'attendeesCount', 'attendees'));
    }
}
