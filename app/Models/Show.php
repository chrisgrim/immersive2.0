<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\DateScope;

class Show extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
	protected $fillable = ['date','event_id'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new DateScope);
    }

	/**
     * Show Model belongs to the Event Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function event() 
    {
        return $this->belongsTo(Event::class);
    }
    
    /**
     * Each Show has many tickets 
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets() 
    {
        return $this->morphMany(Ticket::class, 'ticket');
    }

    public static function saveAlwaysShow($request, $event)
    {

        // check to see if tickets exists for this show and save them as $old_tickets
        if ($event->shows()->exists() && $event->shows()->first()->tickets()->exists()) {
            $old_tickets = $event->shows()->first()->tickets()->get();
        } else {
            $old_tickets = null;
        }

        //  delete old tickets assigned to this show
        foreach($event->shows as $show){
            $show->tickets()->delete();
        }

        // delete all shows assigned to event
        $event->shows()->delete();

        // add showongoing model to event
        $event->showOnGoing()->update([
            'mon' => true,
            'tue' => true,
            'wed' => true,
            'thu' => true,
            'fri' => true,
            'sat' => true,
            'sun' => true,
        ]);

        // create a new single show for the event 6 months from now
        $show = $event->shows()->create([
            'date' => Carbon::now()->addMonths(6)->format('Y-m-d H:i:s'),
        ]);
       
       // update that single show with tickets saved from earlier
        if ($old_tickets) {
            foreach ($old_tickets as $ticket) {
                 $show->tickets()->updateOrCreate([
                    'name' => $ticket['name'],
                ],
                [
                    'description' => $ticket['description'],
                    'currency' => $ticket['currency'],
                    'ticket_price' => $ticket['ticket_price'],
                    'type' => $ticket['type']
                ]);
            }
        }
    }

    /**
     * Saving the shows and tickets to database
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public static function saveNewShows($request, $event)
    {
        // check to see if tickets exists for this show and save them as $old_tickets
        if ($event->shows()->exists() && $event->shows()->first()->tickets()->exists()) {
            $old_tickets = $event->shows()->first()->tickets()->get();
        } else {
            $old_tickets = null;
        }

        // Delete all old shows
        $showDelete = $event->shows()->whereNotIn('date', $request->dateArray)->get();
        foreach($showDelete as $show){
            $show->tickets()->delete();
        }
        $event->shows()->whereNotIn('date', $request->dateArray)->delete();

        //  for each date do this
        foreach( $request->dateArray as $date) {

            //  for each show update or create. This means if I have a lot of overlapping dates I don't delete all of them
            $show = Show::updateOrCreate([
                'date' => $date,
                'event_id' => $event->id
            ]);

            // if tickets were already entered, add them to the new dates
            if ($old_tickets) {
                foreach ($old_tickets as $ticket) {
                    $show->tickets()->updateOrCreate([
                        'name' => $ticket['name'],
                    ],
                    [
                        'description' => $ticket['description'],
                        'currency' => $ticket['currency'],
                        'ticket_price' => $ticket['ticket_price'],
                        'type' => $ticket['type']
                    ]);
                }
            }
        };
    }

    /**
     * Get the showtimes and price range to update the event model
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public static function updateEvent($request, $event)
    {
        //  get the last date based on show type
        if ($request->shows) {
            $type = 's';
            $lastDate = $event->shows()->orderBy('date', 'DESC')->first()->date;
        }
        if ($request->limited) {
            $type = 'l';
            $lastDate = $event->shows()->orderBy('date', 'DESC')->first()->date;
        }
        if ($request->onGoing) {
            $type = 'o';
            $lastDate = $event->shows()->orderBy('date', 'DESC')->first()->date;
        }
        if ($request->always) {
            $type = 'a';
            $lastDate = Carbon::now()->addMonths(6)->format('Y-m-d H:i:s');
        }

        if ($event->status === 'e' && $request->embargo_date === null ) {
            return $event->update([
                'show_times' => $request->showTimes,
                'embargo_date' => $request->embargoDate,
                'closingDate' => $lastDate,
                'showtype' => $type,
                'timezone_id' => $request->timezone ? $request->timezone['id'] : null,
                'status' => 'p'
            ]);
        }

        if ($event->status === 'p' && $request->embargo_date ) {
            $event->update([
                'show_times' => $request->showTimes,
                'embargo_date' => $request->embargoDate,
                'closingDate' => $lastDate,
                'showtype' => $type,
                'timezone_id' => $request->timezone ? $request->timezone['id'] : null,
                'status' => 'e'
            ]);
            return $event->unsearchable();
        }
        
        $event->update([
            'show_times' => $request->showTimes,
            'embargo_date' => $request->embargoDate,
            'closingDate' => $lastDate,
            'showtype' => $type,
            'timezone_id' => $request->timezone ? $request->timezone['id'] : null,
        ]);
    }
}
