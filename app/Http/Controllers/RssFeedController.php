<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class RssFeedController extends Controller
{
    public function feed()
    {
        $events = Event::where('status', 'p')
        ->orderBy('created_at', 'desc')
        ->with('shows')
        ->limit(50)
        ->get();
        return response()->view('rss.feed', compact('events'))->header('Content-Type', 'application/xml');

    }
}
