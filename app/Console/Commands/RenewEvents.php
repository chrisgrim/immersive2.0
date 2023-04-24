<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRenew;
use Carbon\Carbon;

class RenewEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ei:check-event-closing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if events are closing and warn the creators';

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
        $from = Carbon::now()->addDays(3)->startOfDay();
        $to = Carbon::now()->addDays(3)->endOfDay();
        $events = Event::where('status', 'p')->whereBetween('closingDate', [$from, $to])->get();
        foreach ($events as $event) {
            // $user = User::find($event->organizer->user_id)->email;
            // Mail::to($user)->send(new EventRenew($event));
            if ($event->showtype === 'o') {
                Mail::to('kester.corinna@gmail.com')->send(new EventRenew($event));
            }
            Mail::to('chgrim@gmail.com')->send(new EventRenew($event));
        }
    }
}
