<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CityList;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Organizer;
use App\Models\StaffPick;
use App\Models\Featured\Dock;
use App\Models\Curated\Community;
use App\Models\Curated\Post;
use App\Models\Curated\Shelf;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\TitleUpdateRequest;
use Illuminate\Support\LazyCollection;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('index','show', 'fetch');
        $this->middleware('can:update,event')
        ->except(['index','create','show','store','fetch']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        if (Carbon::now()->startOfWeek()->addDays(3) >= Carbon::now()) {
            $thursday = Carbon::now()->startOfWeek()->subDays(4);
            $wednesday = Carbon::now()->startOfWeek()->addDays(2);
        } else {
            $thursday = Carbon::now()->startOfWeek()->addDays(3);
            $wednesday = Carbon::now()->startOfWeek()->addDays(9);
        }
        $staffpicks = StaffPick::where(function($query) use ($thursday, $wednesday){
                $query->whereDate('start_date', '>=', $thursday)
                        ->whereDate('end_date', '<=', $wednesday);
            })
            ->orderBy('rank')
            ->with('event.category')
            ->limit(4)
            ->get();
        $docks = Dock::where('location', 'home')->with(['posts.limitedCards', 'shelves.publishedPosts.limitedCards', 'communities'])->orderBy('order', 'ASC')->get();
        $categories = Category::all();
        $tags = Genre::where('admin', 1)->orderBy('rank', 'desc')->get();
        return view('home.index', compact('categories', 'staffpicks', 'docks', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->hasCreatedOrganizers) {
            return view('events.edit');
        }
        return view('create.initial');
    }

    /**
     * Store a newly created resource in storage. When I first create event I create a location and expectation at the same time. Depending on if the store came from axios or a form I send the data back as a object or a redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->events()->whereNotIn('status', ['p','e'])->count() > 4 && !auth()->user()->isCurator()) {return null;}
        return Event::newEvent($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        if($event->status !== 'p') { return redirect('/');}
        $event->load('category', 'location', 'contentAdvisories', 'contactLevels', 'mobilityAdvisories', 'eventreviews', 'staffpick', 'advisories', 'showOnGoing','interactive_level', 'remotelocations', 'timezone','genres','ratings.user', 'priceranges', 'organizer', 'shows');
        $tickets = $event->shows()->first()->tickets()->orderBy('ticket_price')->get();
        return view('events.show', compact('event', 'tickets'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('create.organizer',compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->deleteEvent($event);
        return auth()->user()->events;
    }


}
