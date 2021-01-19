<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Organizer;

class SitemapController extends Controller
{
    public function event_sitemap(Event $event)
    {
        $events = Event::where('status', 'p')->latest()->get();
        return response()
        ->view('sitemap.event', compact('events'))
        ->header('Content-Type', 'text/xml');
    }

    public function organizer_sitemap(Organizer $organizer)
    {
        $organizers = Organizer::where('status', 'p')->latest()->get();
        return response()
        ->view('sitemap.organizer', compact('organizers'))
        ->header('Content-Type', 'text/xml');
    }
}
