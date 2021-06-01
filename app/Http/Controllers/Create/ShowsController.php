<?php

namespace App\Http\Controllers\Create;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Show;
use App\Models\Timezone;
use App\Models\ShowOnGoing;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ShowsController extends Controller
{
    /**
     * Create a new ShowsController instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('can:update,event');
    }
    /**
     * Show the form for creating a new resource and passing the event variable
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        if ($event->checkEventStatus(3)) return back();
        $event->load('showOnGoing','shows.tickets','timezone');
        $timezones = Timezone::all()->sortBy('offset')->values();
        return view('create.show', compact('event', 'timezones'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        if ($request->shows)  Show::saveNewShows($request, $event);

        if ($request->limited) {
            //check if the start and finish include the week dates
            foreach (CarbonPeriod::create($request->startDate, $request->endDate) as $date) {
                $calendarDays[] = strtolower($date->format('D'));
            }
            $weekDays= array_keys (array_filter($request->week));
            if (empty(array_intersect($calendarDays, $weekDays))) return abort(500, "noOverlap");
            
            ShowOnGoing::saveNewShowOnGoing($request, $event);
            Show::saveNewShows($request, $event);
        }

        if($request->onGoing) {
            ShowOnGoing::saveNewShowOnGoing($request, $event);
            Show::saveNewShows($request, $event);
        }

        if ($request->always) Show::saveAlwaysShow($request, $event);

        Show::updateEvent($request, $event);
        $event->updateEventStatus(4, $request);
        $event = $event->fresh();
        $event->searchable();
    }

    /**
     * Fetch the stored shows and tickets
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Event $event)
    {
        if ($event->showtype == 's' ) {
            return response()->json(array(
                'dates' => $event->shows()->pluck('date'),
                'tickets' => $event->shows()->with('tickets')->get(),
                'showTimes' => $showTimes = $event->show_times,
                'embargo_date' => $event->embargo_date,
            ));
        }
        if($event->showtype == 'l') {
            return response()->json(array(
                'week' => $event->showOnGoing()->first(),
                'dates' => $event->shows()->pluck('date'),
                'tickets' => $event->shows()->with('tickets')->get(),
                'showTimes' => $showTimes = $event->show_times,
                'embargo_date' => $event->embargo_date,
            ));
        }
        if($event->showtype == 'o') {
            return response()->json(array(
                'week' => $event->showOnGoing()->first(),
                'dates' => $event->shows()->pluck('date'),
                'tickets' => $event->shows()->with('tickets')->get(),
                'showTimes' => $showTimes = $event->show_times,
                'embargo_date' => $event->embargo_date,
            ));
        }
        if($event->showtype == 'a') {
            return response()->json(array(
                'week' => $event->showOnGoing()->first(),
                'tickets' => $event->showOnGoing()->with('tickets')->get(),
                'showTimes' => $showTimes = $event->show_times,
                'embargo_date' => $event->embargo_date,
            ));
        }
    }
}
