<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Event;
use App\Models\RemoteLocation;
use Illuminate\Http\Request;
use App\Http\Requests\LocationStoreRequest;


class LocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:update,event');
    }

    /**
     * Show the form for creating a new resource. Eager Load location with event. Load the pivot table that shows which regions are assigned to this event
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        if ($event->checkEventStatus(1)) return back();
        $event->load('location', 'remotelocations');
        $remote = RemoteLocation::where('admin', true)->orWhere('user_id', auth()->user()->id)->get();
        return view('create.location', compact('event', 'remote'));
    }

    /**
     * Show the form for creating a new resource. Eager Load location with event. Load the pivot table that shows which regions are assigned to this event
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Event $event)
    {
        return [
            'location' => $event->location()->first(),
            'pivots' => $event->remotelocations()->get(),
        ];
    }

    /**
     * Send to Location Model
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(LocationStoreRequest $request, Event $event)
    {
        $request->remote ? Location::storeRemoteLocation($request, $event) : Location::storeEventLocation($request, $event);
        $event->updateEventStatus(2, $request);
    }
}
