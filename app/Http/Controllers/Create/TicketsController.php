<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Http\Requests\TicketStoreRequest;

class TicketsController extends Controller
{
    /**
     * Create a new Tickets Controller validation .
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:update,event');
    }
    /**
     * Show the form for creating a new resource and passing the event variable
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        if ($event->checkEventStatus(4)) return back();
        $event->load('showOnGoing','shows.tickets');
        return view('create.ticket', compact('event'));
    }

    /**
     * Fetch the stored shows and tickets
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Event $event)
    {
        return [
            'tickets' => $event->shows()->with('tickets')->get(),
            'ticketUrl' => $event->ticketUrl,
        ];
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(TicketStoreRequest $request, Event $event)
    {
        // goes through each show attached to the event
        foreach( $event->shows as $show) {

            //  gets a list of all submitted tickets
            foreach ($request->tickets as $ticket) {
                $ticketname[] = $ticket['name'];
            };

            //  checks name of tickets against any tickets already assigned to show and deletes any that aren't there
            $show->tickets()->whereNotIn('name', $ticketname)->delete();

            //  goes through and updates or creates new tickets for each show 

            foreach ($request->tickets as $ticket) {
                 $show->tickets()->updateOrCreate([
                    'name' => $ticket['name'],
                ],
                [
                    'description' => $ticket['description'],
                    'currency' => $ticket['currency'],
                    'ticket_price' => $ticket['ticket_price'],
                    'type' => $ticket['type']['type']
                ]);
            }
        };

        //  Delete previous price ranges
        $event->priceranges()->delete();

        //  Create new Price ranges based off request tickets and create an array from them
        foreach ($request->tickets as $ticket) {
            $event->priceranges()->Create([
                'price' => $ticket['ticket_price']
            ]);
            $currency = $ticket['currency'];
            $array[] = $ticket['ticket_price'];
            $name[] = $ticket['name'];
            $types[] = $ticket['type']['type'];
        }

        $pricerange = Ticket::getPriceRange($array, $types, $currency);

        // Add price range string to event
        $event->update([
            'price_range' => $pricerange,
            'ticketUrl' => $request->ticketUrl,
            'call_to_action' => $request->callAction,
        ]);

        $event->updateEventStatus(5, $request);
        $event = $event->fresh();
        $event->searchable();
    }
}
