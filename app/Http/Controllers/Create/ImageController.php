<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MakeImage;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:update,event');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        if ($event->checkEventStatus(7)) return back();
        return view('create.image', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->inProgress() ? MakeImage::saveNewImage($request, $event, 1280, 720, 'event') : MakeImage::updateImage($request, $event, 1280, 720, 'event');
        $event->updateEventStatus(8, $request);
        return $event;

    }
}
