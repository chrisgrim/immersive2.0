<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\DescriptionStoreRequest;

class DescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:update,event');
    }

    /**
     * Show the form for creating a new resource. $Pivots gets the genres assigned to the event
     $genres gets all the genres in the list.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        if ($event->checkEventStatus(5)) return back();
        return view('create.description', compact('event'));
    }

     /**
     * Show the form for creating a new resource. $Pivots gets the genres assigned to the event
     $genres gets all the genres in the list.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Event $event)
    {
        return $event;
    }

    /**
     * Store Description
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(DescriptionStoreRequest $request, Event $event)
    {  
        $event->update([
            'description' => $request->description,
            'websiteUrl' => $request->websiteUrl
        ]);
        $event->updateEventStatus(6, $request);
        $event = $event->fresh();
        $event->searchable();

    }
}
