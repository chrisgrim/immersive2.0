<?php

namespace App\Http\Controllers\Creation;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\RemoteLocation;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Event;

class IdeaController extends Controller
{
    public function experienceType(Event $event)
    {
        $event->load('location', 'remotelocations', 'shows','organizer.users','category');
        $tags = Genre::where('admin', true)->orWhere('user_id', auth()->user()->id)->get();
        $categories = Category::all();
        $remote = RemoteLocation::where('admin', true)->orWhere('user_id', auth()->user()->id)->get();
        return view('creation.experience.type', compact('event', 'categories', 'tags', 'remote'));
    }
}