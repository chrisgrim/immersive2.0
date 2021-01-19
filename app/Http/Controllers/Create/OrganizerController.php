<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use App\Models\Organizer;
use App\Models\Event;
use Illuminate\Http\Request;


class OrganizerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Fetch the users created events
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, Organizer $organizer)
    {
        return Organizer::getOrganizerEvents();
    }

}
