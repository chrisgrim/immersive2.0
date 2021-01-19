<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ShowOnGoing extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = ['event_id','date','mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    /**
     * Each Show has many tickets 
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets() 
    {
        return $this->morphMany(Ticket::class, 'ticket');
    }

    /**
     * Saving the shows and tickets to database
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public static function saveNewShowOnGoing($request, $event)
    {
        if ($request->limited) 
        {
            $period = CarbonPeriod::create(new Carbon($request->start_date), new Carbon($request->end_date));
        } 
        elseif($request->onGoing) 
        {
            $initialdate = new Carbon($request->start_date);
            $period = CarbonPeriod::create(new Carbon($request->start_date), $initialdate->addMonths(6));
        } 
        else
        {
            $period = CarbonPeriod::create(Carbon::now()->startOfDay(), Carbon::now()->startOfDay()->addMonths(6));
        }

        $ongoing = ShowOnGoing::updateOrCreate(
            [
                'event_id' => $event->id,
            ],
            [
                'mon' => $request->week['mon'],
                'tue' => $request->week['tue'],
                'wed' => $request->week['wed'],
                'thu' => $request->week['thu'],
                'fri' => $request->week['fri'],
                'sat' => $request->week['sat'],
                'sun' => $request->week['sun'],
            ]
        );
        $dates=[];
        foreach ($period as $date) {
            if ($date->isMonday() && $request->week['mon']) {$dates[]=$date->format('Y-m-d H:i:s');}
            if ($date->isTuesday() && $request->week['tue']) {$dates[]=$date->format('Y-m-d H:i:s');}
            if ($date->isWednesday() && $request->week['wed']) {$dates[]=$date->format('Y-m-d H:i:s');}
            if ($date->isThursday() && $request->week['thu']) {$dates[]=$date->format('Y-m-d H:i:s');}
            if ($date->isFriday() && $request->week['fri']) {$dates[]=$date->format('Y-m-d H:i:s');}
            if ($date->isSaturday() && $request->week['sat']) {$dates[]=$date->format('Y-m-d H:i:s');}
            if ($date->isSunday() && $request->week['sun']) {$dates[]=$date->format('Y-m-d H:i:s');}
        }
        $request->request->add(['dates' => $dates]);
    }
}
