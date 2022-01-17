<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Organizer;
use App\Models\Event;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Loads the users created events
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('events.edit');
    }

    /**
     * Fetch the users created events
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, Organizer $organizer)
    {
        if ($request->state === 'past') {
            return $organizer->pastEvents()->paginate(7);
        }
        if ($request->state === 'current') {
            return $organizer->inProgressEvents()->paginate(7);
        }
    }

    /**
     * Updates the event to be archived
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function onArchive(Request $request, Event $event)
    {
        $event->update(['archived' => true]);
        return $event->organizer->load('listedEvents', 'archivedEvents');
    }

    /**
     * Updates the event to be archived
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function offArchive(Request $request, Event $event)
    {
        $event->update(['archived' => false]);
        return $event->organizer->load('listedEvents', 'archivedEvents');
    }

    /**
     * Returns Review Page in Creation Process
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function review(Event $event)
    {
        $this->authorize('finalize', $event);
        $event->load('category', 'location', 'contentAdvisories', 'contactLevels', 'mobilityAdvisories', 'advisories', 'showOnGoing', 'remotelocations', 'timezone', 'genres');
        $tickets = $event->shows()->first()->tickets()->orderBy('ticket_price')->get();
        return view('create.review', compact('event', 'tickets'));
    }

    /**
     * Submits the event
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function submit(Event $event) 
    {
        $this->authorize('finalize', $event);
        $event->update(['status' => 'r',]);
        $event->finalizeEvent($event);
    }

    /**
     * Returns user back to homepage with a completed popup
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function completed(Event $event)
    {
        return redirect('create/events/edit')->with('submitted', 'Your event has been submitted for review');
    }

}
