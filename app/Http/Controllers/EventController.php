<?php

namespace App\Http\Controllers;

use App\Mail\EventTicketEmail;
use Log;
use App\Models\User;
use App\Models\Event;
use App\Services\Paypal;
use App\Models\EventOrder;
use App\Models\TicketType;
use App\Models\ReportEvent;
use App\Models\Setting;
use App\Traits\VideoUpload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EventCategory;
use App\Models\UserTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Mail;


class EventController extends Controller
{
    use VideoUpload;

    public function createEvent(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'event_name' => 'required|string',
            'date' => "required|date_format:Y-m-d", // Format must match the provided example
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'nullable|date_format:H:i:s',
            'location' => 'required|string',
            'privacy' => 'required|string',
            'description' => 'required|string',
            'event_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $eventData = [
            'event_name' => $validatedData['event_name'],
            'date' => $validatedData['date'],
            'start_time' => $validatedData['start_time'],
            'location' => $validatedData['location'],
            'privacy' => $validatedData['privacy'],
            'description' => $validatedData['description'],
            'created_by' => $user->id,
        ];

        // Check if end_time is provided before including it in the data array
        if (isset($validatedData['end_time'])) {
            $eventData['end_time'] = $validatedData['end_time'];
        }

        $event = Event::create($eventData);

        if ($request->hasFile('event_image')) {
            $event_image = $this->manualStoreMedia($request->file('event_image'))['name'];
            $event->addMedia(storage_path('tmp/uploads/' . basename($event_image)))->toMediaCollection('event_image');
        }

        $event->load('media');

        $response = [
            'message' => "Events Created Successfully",
            'data' => [
                'event_name' => $event->event_name,
                'date' => $event->date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'location' => $event->location,
                'privacy' => $event->privacy,
                'description' => $event->description,
                'created_by' => $event->created_by,
                'event_image' => $event->getMedia('event_image')->map->getUrl(),
            ],
        ];

        return response()->json($response, 200);
    }



    public function updateEvent(Request $request, $eventId)
    {
        $user = Auth::user();

        $eventUpdate = Event::findOrFail($eventId);

        $validatedData = $request->validate([
            'event_name' => 'sometimes|string',
            'date' => 'sometimes|date_format:Y-m-d',
            'start_time' => 'sometimes|date_format:H:i:s',
            'end_time' => 'sometimes|date_format:H:i:s',
            'location' => 'sometimes|string',
            'privacy' => 'sometimes|string',
            'description' => 'sometimes|string',
            // 'event_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

          // Validate event_image separately
            if ($request->hasFile('event_image')) {
                $request->validate([
                    'event_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
            }

           // Check if the logged-in user is the creator of the event
            if ($user->id !== $eventUpdate->created_by) {
                return response()->json(['message' => 'You do not have permission to edit this event'], 403);
            }

        // Update the event fields based on the provided data
        $eventUpdate->update($validatedData);

         // Update the images if provided
        // Handle image update
        if ($request->hasFile('event_image')) {
           // Check if images exist and delete them if they do
           if ($eventUpdate->hasMedia('event_image')) {
            $eventUpdate->clearMediaCollection('event_image');
        }
            $event_image = $this->manualStoreMedia($request->file('event_image'))['name'];
            $eventUpdate->addMedia(storage_path('tmp/uploads/' . basename($event_image)))->toMediaCollection('event_image');
        }

        $eventUpdate->save(); // Save the updated event

        $eventUpdate->load('media');

        $response = [
            'message' => 'Event Updated Successfully',
            'data' => [
                'event_name' => $eventUpdate->event_name,
                'date' => $eventUpdate->date,
                'start_time' => $eventUpdate->start_time,
                'end_time' => $eventUpdate->end_time,
                'location' => $eventUpdate->location,
                'privacy' => $eventUpdate->privacy,
                'description' => $eventUpdate->description,
                'created_by' => $eventUpdate->created_by,
                'event_image' => $eventUpdate->getMedia('event_image')->map->getUrl(),
            ],
        ];

        return response()->json($response, 200);
    }


    public function deleteEvent($eventId)
    {
        $user = Auth::user();

        $deleteEvent = Event::findOrFail($eventId);

        // Check if the logged-in user is the creator of the event
        if ($user->id !==  $deleteEvent->created_by) {
            return response()->json(['message' => 'You do not have permission to delete this event'], 403);
        }

         // Clear the media collection associated with the event
         $deleteEvent->clearMediaCollection('event_image');

         $deleteEvent->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }

    public function fetchEvents(Request $request)
    {
        $events = Event::with(['media', 'ticketTypes'])->get();

        $eventsData = $events->sortByDesc('created_at');

        return response()->json([
            'message' => 'Events fetched successfully',
            'events' => $eventsData,
        ], 200);

    }

    public function fetchSingleEvent($eventId)
    {
        $event = Event::with('attendees', 'event_category','ticketTypes')
            ->where('id', $eventId)
            ->first();

        if(!$event){
            return response()->json(["message" => "Event not found!"], 400);
        }

        $response = [
            'id' => $event->id,
            'name' => $event->name,
            'discount' => $event->discount,
            'start_time' => $event->start_date,
            'end_time' => $event->end_date,
            'name' => $event->name,
            'discount' => $event->discount,
            'start_date' => $event->start_date,
            'end_time' => $event->end_date,
            'location' => $event->location,
            'tags' => $event->tags,
            'description' => $event->description,
            'created_at' => $event->created_at,
            'updated_at' => $event->updated_at,
            'images' => $event->getMedia('image')->map->getUrl(),
            'attendees' => $event->attendees,
            'event_category'=>$event->event_category,
            'ticket_type'=>$event->ticketTypes,
        ];

        return response()->json($response, 200);

    }



    // public function attendEvent(Request $request, $eventId)
    // {
    //     $user = $request->user();
    //     $event = Event::findOrFail($eventId);

    //     // Check if the user is already attending the event
    //     if ($user->events->contains($event)) {
    //         return response()->json(['message' => 'You are already attending this event.'], 400);
    //     }

    //     // Check if the authenticated user is the event creator
    //     // if ($user->id == $event->createdBy->id) {
    //     //     return response()->json(['message' => 'You Cannot Join An Event You Created.'], 400);
    //     // }

    //     // Check if the event is private
    //     if ($event->privacy === 'private') {
    //         // Get the family IDs of the event creator and the user
    //         $creatorFamilyIds = $event->createdBy->families->pluck('id')->toArray();
    //         $userFamilyIds = $user->families()->pluck('family_id')->toArray();

    //         // Check if there is any common family ID where both users are accepted
    //         $commonFamilyIds = array_intersect($creatorFamilyIds, $userFamilyIds);

    //         // Check if both users are accepted in at least one common family
    //         $bothAcceptedInCommonFamily = count($commonFamilyIds) > 0;

    //         if ($bothAcceptedInCommonFamily) {
    //             // If there is at least one common family where both users are accepted, allow access to the event
    //             $event->attendees()->attach($user);
    //             return response()->json(['message' => 'You are now attending this private event.'], 200);
    //         }

    //         // If no common family is found where both users are accepted, deny access
    //         return response()->json(['message' => 'You are not allowed to attend this private event.'], 403);
    //     }


    //     // Attach the user to the event's attendees for non-private events
    //     $event->attendees()->attach($user);
    //     return response()->json(['message' => 'You are now attending this event.'], 200);
    // }

    public function attendEvent(Request $request, $eventId)
    {
        $user = $request->user();
        $event = Event::find($eventId);



        // check if event exists
        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        // Check if the user is already attending the event
        if ($user->events->contains($event)) {
            return response()->json(['message' => 'You are already attending this event.'], 400);
        }

        $referenceId = Str::uuid()->toString();

        $request->validate([
            'amount' => 'required|numeric',
            'quantity' => 'numeric',
        ]);

        // Retrieve the reference ID and amount from the request
        $referenceId = $referenceId;
        $amount = $request->input('amount');

         // Retrieve the selected ticket type and quantity from the request
        $ticketTypeId = $request->input('ticket_type');
        $quantity = $request->input('quantity');

         // Check if the selected ticket type is associated with the event
            if (!$event->ticketTypes->contains($ticketTypeId)) {
                return response()->json(['message' => 'Selected ticket type is not associated with this event.'], 400);
            }

        // Retrieve the selected ticket type and its price
        $selectedTicket = TicketType::findOrFail($ticketTypeId);

        $ticketPrice = $selectedTicket->price;

        // Check if the provided amount matches the ticket type price
        // if ($amount != $ticketPrice * $quantity) {
        //     return response()->json(['message' => 'Provided amount does not match the ticket price.'], 400);
        // }

         // Check if an event order already exists for the user and event
            $existingOrder = EventOrder::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

            // If event order exists
        if ($existingOrder) {
            // Check if there are any pending transactions for the user that are not accepted
                $pendingTransactions = UserTransaction::where('user_id', $user->id)
                ->where('status', '<>', 'COMPLETED')
                ->get();

                // Delete any pending transactions that are not accepted
                foreach ($pendingTransactions as $transaction) {
                $transaction->delete();
                }

        } else {


            // Create a new event order only if one doesn't exist already
            $userEventOrder = new EventOrder;
            $userEventOrder->user_id = $user->id;
            $userEventOrder->event_id = $event->id;
            $userEventOrder->ticket_type_id = $ticketTypeId;
            $userEventOrder->quantity = $quantity;
            $userEventOrder->status = 'pending';
            $userEventOrder->save();
        }

        $paypal = new Paypal;

        // Initiate the PayPal checkout process
        $response = $paypal->initiatePaypalCheckout($referenceId, $amount);

        $checkoutTransaction = new UserTransaction;

        $checkoutTransaction->user_id =$request->user()->id;
        $checkoutTransaction->paypal_checkout_id =   $response['id'];
        $checkoutTransaction->status =    $response['status'];
        $checkoutTransaction->save();


        return response()->json(["redirectUrl"=>$response['links'][1]['href']]);
    }

    function checkoutSuccess(string $referenceId){
        $paypal = new Paypal;

        $response = $paypal->confirmPayPalCheckout($referenceId);

        if ($response && isset($response['status'])) {
            // Update the transaction details if found
            $checkoutTransactionDetails = UserTransaction::where('paypal_checkout_id', $referenceId)->first();
            if ($checkoutTransactionDetails) {
                $checkoutTransactionDetails->update([
                    "status" => $response['status'],
                ]);
            }
            return $response;
        } else {
            // Handle the case where the checkout response is invalid
            return response()->json(['error' => 'Invalid checkout response'], 400);
        }

    }

    public function eventPaymentSuccess(Request $request, $eventId, $userId)
    {
        $user = User::findOrFail($userId);
        $event = Event::findOrFail($eventId);

        $paypal = new Paypal;

      $response = $this->checkoutSuccess($request->reference_id);


        if ($response && isset($response['status'])) {
            $updateEventOrder = EventOrder::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();
            if ($updateEventOrder && $response['status'] === 'COMPLETED') {

                 // Generate a unique ticket ID
                 $ticketId = strtoupper(Str::random(10));
                // Update event order status to "Accepted"
                $updateEventOrder->update([
                    "status" => "Accepted"
                ]);

                 // Retrieve the quantity and ticket type ID from the event order
                $quantity = $updateEventOrder->quantity;
                $ticketTypeId = $updateEventOrder->ticket_type_id;


                // Attach the user to the event as attendee
                $event->attendees()->attach($user,
                 ['ticket_id' => $ticketId,
                 'quantity' => $quantity,
                 'ticket_type_id' => $ticketTypeId
                ]);

                $ticketType = TicketType::where('id', $ticketTypeId)->first();
                $total = $ticketType->price * $quantity;
                $logos = Setting::latest()->first();

                Mail::to($user->email)->send(new EventTicketEmail($user, $event ,$ticketType, $ticketId, $quantity, $total, $logos));
                
                return response()->json(['message' => 'You are now attending this event'], 200);
            } else {
                return response()->json(['error' => 'Event order not found or checkout not completed'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid checkout response'], 400);
        }
    }

    public function searchEvents(Request $request)
    {
        // Retrieve search parameters
        $eventName = $request->input('event_name');
        $location = $request->input('location');

        // Perform the search based on event name and location
        $events = Event::query()
            ->where('name', 'like', '%' . $eventName . '%')
            ->where('location', 'like', '%' . $location . '%')
            ->get();

        // Check if any events were found
        if ($events->isEmpty()) {
            // No events found matching the search query
            return response()->json(['message' => 'No events found matching your search.'], 200);
        }

        // Events found, return them as JSON response
        return response()->json($events, 200);
    }

    public function searchByCategory(Request $request)
    {
        // Retrieve the selected category ID from the request
        $categoryId = $request->input('category');
        // dd($categoryId);

        // Fetch events based on the selected category ID by joining the event and event_category tables
        $events = Event::where('event_category_id', $categoryId)
                        ->get();

        if($events->isEmpty()){
            return response()->json(['message' => 'No Events Found For The Selected Category'], 200);
        }

            // Return the events as JSON response
        return response()->json($events, 200);

    }

    public function getAllEventCategories()
    {
        // Retrieve all event categories
        $eventCategories = EventCategory::all();

        // Return the event categories as JSON response
        return response()->json($eventCategories, 200);
    }

    public function getUserEventOrder()
    {
        $user = Auth::user();

        $eventOrders = EventOrder::where('user_id', $user->id)
        ->with('event')
        ->get();

        if($eventOrders->isEmpty()){
            return response()->json(['message' => 'No Event Order'], 400);
        }else{
            return response()->json($eventOrders, 200);
        }
    }


    public function leaveEvent(Request $request, $eventId)
    {
        $user = $request->user();

        $event = Event::findOrFail($eventId);

        // Check if the user is attending the event
        if ($user->events->contains($event)) {
            // Detach the user from the event's attendees
            $event->attendees()->detach($user);
            return response()->json(['message' => 'You have left the event.'], 200);
        } else {
            return response()->json(['message' => 'You are not attending this event.'], 400);
        }
    }




    public function upcomingEvents(Request $request)
    {
        $user = $request->user();

        $currentDateTime = now();

        $upcomingEvents = Event::with('attendees')
        ->where('start_date', '>', $currentDateTime->toDateTimeString())
        ->orderBy('start_date', 'asc')
        ->get();


            $upcomingEvents->load('media', 'attendees');

            $eventsWithImagesAndAttendees = $upcomingEvents->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'discount' => $event->discount,
                    'start_date' => $event->start_date,
                    'end_time' => $event->end_date,
                    'location' => $event->location,
                    'tags' => $event->tags,
                    'description' => $event->description,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                    'images' => $event->getMedia('image')->map->getUrl(),
                    'attendees' => $event->attendees,
                    'event_category'=>$event->event_category,
                    'ticket_type'=>$event->ticketTypes,
                ];
            });

            return response()->json([
                'message' => 'Upcoming events fetched successfully',
                'events' => $eventsWithImagesAndAttendees,
            ], 200);
    }

    public function randomEvents(Request $request)
    {
        $user = $request->user();

        $currentDateTime = now();

        $randomEvents = Event::with('attendees')
        ->where('start_date', '>', $currentDateTime->toDateTimeString())
        ->inRandomOrder()  //fetch event in random order
        ->get();

        $randomEvents->load('media', 'attendees');

        $allRandomEvents = $randomEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'discount' => $event->discount,
                'start_date' => $event->start_date,
                'end_time' => $event->end_date,
                'location' => $event->location,
                'tags' => $event->tags,
                'description' => $event->description,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
                'images' => $event->getMedia('image')->map->getUrl(),
                'attendees' => $event->attendees,
                'event_category'=>$event->event_category,
                'ticket_type'=>$event->ticketTypes,
            ];
        });

        return response()->json([
            'message' => 'Random events fetched successfully',
            'events' => $allRandomEvents,
        ], 200);
    }

    public function userCreatedEvents(Request $request)
    {
        $user = $request->user();

        $userCreatedEvents = Event::with('createdBy', 'attendees')
            ->where('created_by', $user->id) // Fetch events created by the user
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->get();

            $userCreatedEvents->load('media', 'attendees');

            $allCreatedEvents = $userCreatedEvents->map(function ($event) {
                return [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'date' => $event->date,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'location' => $event->location,
                    'privacy' =>  $event->privacy,
                    'description' => $event->description,
                    'created_by' => $event->createdBy,
                    'event_image' => $event->getMedia('event_image')->map->getUrl(),
                    'attendees' => $event->attendees,
                ];
            });

        return response()->json([
            'message' => 'Events created by the user fetched successfully',
            'events' => $allCreatedEvents,
        ], 200);
    }


    public function userAttendingEvents(Request $request)
    {
        $user = $request->user();

        $currentDateTime = now();

        $userAttendingEvents = $user->events()
             ->where('start_date', '>', $currentDateTime->toDateTimeString())
             ->with('attendees')
             ->orderBy('start_date', 'asc')
             ->get();

        $userAttendingEvents->load('media', 'attendees');

        $allAttendingEvents = $userAttendingEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'discount' => $event->discount,
                'start_date' => $event->start_date,
                'end_time' => $event->end_date,
                'location' => $event->location,
                'tags' => $event->tags,
                'description' => $event->description,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
                'images' => $event->getMedia('image')->map->getUrl(),
                'attendees' => $event->attendees,
                'event_category'=>$event->event_category,
                'ticket_type'=>$event->ticketTypes,
            ];
        });

        if($allAttendingEvents->isEmpty()){
            return response()->json(['message' => 'No Attending Event']);
        }

        return response()->json([
            'message' => 'Events user is attending fetched successfully',
            'events' => $allAttendingEvents,
        ], 200);
    }




    public function attendedEvents(Request $request)
    {
        $user = $request->user();

        $currentDateTime = now();

        $attendedExpiredEvents = Event::with('createdBy', 'attendees')
            ->whereIn('id', $user->events->pluck('id')) // Fetch events user attended
            ->where('date', '<', $currentDateTime->toDateString())
            ->orWhere(function ($query) use ($currentDateTime) {
                $query->where('date', $currentDateTime->toDateString())
                    ->where('start_time', '<', $currentDateTime->format('H:i:s'));
            })
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

            $attendedExpiredEvents->load('media', 'attendees');

            $allAttendedEvents = $attendedExpiredEvents->map(function ($event) {
                return [
                    'id' => $event->id,
                    'event_name' => $event->event_name,
                    'date' => $event->date,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'location' => $event->location,
                    'privacy' =>  $event->privacy,
                    'description' => $event->description,
                    'created_by' => $event->createdBy,
                    'event_image' => $event->getMedia('event_image')->map->getUrl(),
                    'attendees' => $event->attendees,
                ];
            });


        return response()->json([
            'message' => 'Attended and expired events fetched successfully',
            'events' => $allAttendedEvents,
        ], 200);
    }

    public function reportEvent(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $request->validate([
            'message' => 'required|string',
        ]);
        if($event->created_id !== auth()->user()->id )
        {
        $reportEvent = ReportEvent::create([

                'user_id' => auth()->user()->id,
                'event_id' => $event_id,
                'message' => $request->input('message'),
            ]);

            $response = [
                'message' => 'reports created successfully',
                'reportPost' => $reportEvent,
            ];
            return response()->json($response, 200 );
        }else{
            return response()->json(['message' => 'Methods not allowed', 405]);

        }


    }


}
