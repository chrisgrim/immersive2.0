<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PublishEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ei:publish-embargoed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish events that have an embargo date';

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
     * @return mixed
     */
    public function handle()
    {
        $events = Event::whereNotNull('embargo_date')->get();
        foreach ($events as $event) {
            if ($event->embargo_date <= Carbon::now() && $event->status == 'e' ) {
                $event->update([
                    'status' => 'p',
                    'embargo_date' => null,
                ]);
            };
        };
    }
}
