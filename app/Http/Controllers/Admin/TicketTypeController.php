<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;
use App\Models\Event;

class TicketTypeController extends Controller
{
   

    public function create(Request $request)
    {
        $events = Event::all();
        
        $selectedEventId = $request->input('eventId');

        $ticketTypes= TicketType::where('event_id', $selectedEventId)->get();

        return view('admin.ticketType.create', compact('events', 'ticketTypes', 'selectedEventId'));

    }

    public function store(Request $request)
    {
        $eventId = $request->input('eventId');
        $typeId = $request->input('ticketTypeId');
        $name = $request->input('ticket_type_manual');
        $ticketType = $request->input('ticket_type');

      
        $existingTicketType = TicketType::where('id', $typeId)
                                            ->first();

        $existingTicketType2 = TicketType::where('name', $name)
                                        ->where('event_id', $eventId)
                                        ->first();
                                        
        $existingTicketType3 = TicketType::where('name', $ticketType)
                                            ->where('event_id', $eventId)
                                            ->first();
                                            
        if ($existingTicketType) {

            $existingTicketType->update([
            'price' => $request->input('ticket_price'),
            ]);

            return redirect()->route('admin.events.index');
        }

        if ($existingTicketType2) {

            $existingTicketType2->update([
                'price' => $request->input('ticket_price'),
            ]);

            return redirect()->route('admin.events.index');
        } 

        if ($existingTicketType3) {

            $existingTicketType3->update([
                'price' => $request->input('ticket_price'),
            ]);

            return redirect()->route('admin.events.index');
        } 

        if ($name) {
        
            TicketType::create([
            'event_id' => $eventId,
            'name' => $name,
            'price' => $request->input('ticket_price'),
            ]);
            
            return redirect()->route('admin.events.index');
        }

        if ($ticketType) {

            TicketType::create([
                'event_id' => $eventId,
                'name' => $ticketType,
                'price' => $request->input('ticket_price'),
                ]);

                return redirect()->route('admin.events.index');
        }

    }
}
