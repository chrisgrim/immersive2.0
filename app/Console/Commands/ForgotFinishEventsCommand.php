<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Mail\EventReminder;
use App\Mail\OrganizerReminder;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Models\Organizer;

class ForgotFinishEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ei:check-unfinished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if user has created event/submitted';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // //Get list of organizers created in last 7 days
        // $organizers = Organizer::where('created_at', '>=', Carbon::now()->subDays(7))->get();

        // foreach ($organizers as $organizer) {
        //     // if the number of days passed since creation is over 5 then return those organizers
        //     if ($organizer->created_at->diff(Carbon::now())->days > 5 ) {
        //         // get the list of organizers that have not created an event
        //         if(!$organizer->events()->exists()){
        //             //this is organizers who we need to message. Get organizer user and make sure we havent already messaged them from previous event
        //             if ($organizer->user->reminder < 1 ) {
        //                 $attributes = [
        //                     'organizer' => $organizer->name,
        //                     'name' => $event->user->name,
        //                 ];
        //                 Mail::to('chgrim@gmail.com')->send(new OrganizerReminder($attributes));
        //                 // Mail::to($event->user->email)->send(new OrganizerReminder($attributes));
        //                 // $event->user->update(['reminder' => 1]);
        //             }
        //         }
        //     }
        // }

        //Get list of events created in last 24 hours
        $events = Event::where('created_at', '>=', Carbon::now()->subHours(24))->get();

        foreach ($events as $event) {
            // if the number of hours passed since creation is over 23 then return those events
            if ($event->created_at->diff(Carbon::now())->h > 23 ) {
                // get the list of events that are not published
                if( $event->status != 'p' && $event->status != 'n' && $event->status != 'r' && $event->status != 'e'){
                    //these are the events who we need to message. Get Event user and make sure we havent already messaged them from previous event
                    if ($event->user->reminder < 2) {     
                        $attributes = [
                            'event' => $event->name ? $event->name : 'Your event',
                            'name' => $event->user->name,
                        ];
                        Mail::to('chgrim@gmail.com')->send(new EventReminder($attributes));
                        // Mail::to($event->user->email)->send(new EventReminder($attributes));
                        // $event->user->update(['reminder' => 2]);
                    }
                }
            }
        }
    }
}
