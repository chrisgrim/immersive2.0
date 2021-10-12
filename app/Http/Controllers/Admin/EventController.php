<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\MakeImage;
use App\Models\Message;
use App\Models\Events\EventRequest;
use App\Models\AdminArea;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\EventComments;
use App\Mail\EventApproved;
use App\Mail\EventRejected;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Checks for admin before allowing controller access
     *
     * @return \Illuminate\Http\__construct
     */
    public function __construct()
    {
        $this->middleware('moderator');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adminArea.events');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load('category', 'location', 'contentAdvisories', 'contactLevels', 'mobilityAdvisories', 'eventreviews', 'staffpick', 'advisories', 'showOnGoing','interactive_level', 'remotelocations', 'timezone','genres','ratings.user');
        $tickets = $event->shows()->first()->tickets()->orderBy('ticket_price')->get();
        return view('adminArea.show', compact('event', 'tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        // return Event::where('status','p')->paginate(30);
        return Event::where('status','p')
            ->orWhere('status','e')
            ->with('user','clicks')
            ->when(request('date') === 'published', function ($q) {
                return $q->orderByDesc('published_at');
            })
            ->when(request('date') === 'updated', function ($q) {
                return $q->orderByDesc('updated_at');
            })
            ->paginate(15);
    }

    /**
     * List of Events ready to be approved
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function queues()
    {
        return view('adminArea.approval');
    }

    /**
     * Get latest events
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchQue()
    {
        return Event::where('status', 'r')->with('organizer')->get();
    }

    /**
     * Event Finalization Page
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function finalize(Event $event)
    {
        $event->load('category', 'location', 'contentAdvisories', 'contactLevels', 'mobilityAdvisories', 'eventreviews', 'staffpick', 'advisories', 'showOnGoing','interactive_level', 'remotelocations', 'timezone', 'genres');
        $tickets = $event->shows()->first()->tickets()->orderBy('ticket_price')->get();
        $eventExists = Event::where('slug', Str::slug($event->name))->where('id', '!=', $event->id)->first();
        return view('adminArea.showapproval',compact('event', 'tickets', 'eventExists'));
    }

    /**
     * Changes the organizer of a specific event
     *
     * @return \Illuminate\Http\__construct
     */
    public function changeOrganizer(Request $request, Event $event)
    {
        $event->update([
            'user_id' => $request->user_id,
            'organizer_id' => $request->id
        ]);
    }

    /**
     * Approve Event ]
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function approve(Event $event)
    {
        $event = $event->load('user', 'timezone','organizer');
        if ($event->organizer->status != 'p') {
            $event->organizer->update(['status' => 'p']);
        }
        $slug = Event::finalSlug($event);
        MakeImage::finalize($event, $slug, 'event', null);
        if ($event->embargo_date && $event->embargo_date > Carbon::now()) {
            $event->update([
                'status' => 'e',
                'slug' => $slug,
                'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            if(auth()->id() != $event->user->id ) {
                Message::eventnotification($event, 'Thanks, your event has been approved and will be displayed on your chosen date.', $slug);
            }
            
        } else {
            $event->update([
                'status' => 'p',
                'slug' => $slug,
                'published_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            if(auth()->id() != $event->user->id ) {
                Message::eventnotification($event, 'Thanks, your event has been approved!', $slug);
            }
        }
        if(auth()->id() != $event->user->id ) {
            Mail::to($event->user)->send(new EventApproved($event));
        }
        // AdminArea::storeAirtable($event);
    }

    /**
     * Fail Event Creates a new moderator comment in the table, then emails the user that comment. Finally it assigns a value of hasIssues to the event database table.
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function fail(Request $request, Event $event)
    {
        if(auth()->id() != $event->user->id ) {
            $Message = Message::eventnotification($event, 'denied', $request);
            Mail::to($event->user)->send(new EventComments($Message, $event));
        }
        $event->update([
            'status' => 'n',
        ]);
    }

    /**
     * Rejects the event without letting the user have the ability to tweak
     *
     * @param  \App\AdminArea  $adminArea
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, Event $event)
    {
        if(auth()->id() != $event->user->id ) {
            Message::eventnotification($event, 'denied', $request)->load('event');
            Mail::to($event->user)->send(new EventRejected($event));
        }

        $event->delete();
    }

    /**
     * Gets a list of all current event requests
     */
    public function eventRequests()
    {
        return EventRequest::where('status', 'r')->paginate(50);
    }

    /**
     * Respond to Reqest
     */
    public function respondRequest(Request $request, EventRequest $EventRequest)
    {
        Message::eventnotification($EventRequest->event, $request->textarea, null);
        $EventRequest->update([ 'status' => 'p' ]);
    }

    /**
     * Remove Reqest
     */
    public function editTitle(Request $request, EventRequest $EventRequest)
    {
        $EventRequest->event->update([
            'name' => $request->input
        ]);
    }
}
