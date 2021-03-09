<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Events\EventRequest;
use Response;
use Illuminate\Http\Request;
use App\Http\Requests\TitleStoreRequest;

class TitleController extends Controller
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
     * Returns Title Page in Creation Process
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        return view('create.title', compact('event'));
    }

    /**
     * Fetches the current Event Title for the creation process. This involves the timestamp process
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function fetch(Event $event)
    {
        return $event;
    }

    public function changeTitle(Request $request, Event $event)
    {
        $event->eventRequest()->create([
            'request' => $request->changeName
        ]);
    }

    /**
     * Updates the Event Title and resubmit if they change the title
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(TitleStoreRequest $request, Event $event)
    {
        if ($event->exists($event, $request)) return Response::json(['errors' => ['name' => 'same name']], 404); 

        $event->update([ 
            'name' => $request->name,
            'tag_line' => $request->tagLine,
        ]);

        $event->updateEventStatus(1, $request);


    }
}
