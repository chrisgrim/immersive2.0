<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Mail\Newsletter;
use Log;

class KathNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ei:send-katnewsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out weekly newsletter';

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
        $events = Event::where('status', 'p')->whereDate('created_at', '>=', date('Y-m-d H:i:s',strtotime('-7 days')) )->with('genres')->get();
        Mail::to('chgrim@gmail.com')->send(new Newsletter($events));
        // Mail::to('noah@noproscenium.com')->send(new Newsletter($events));
        Mail::to('kathryn@noproscenium.com')->send(new Newsletter($events));
        Log::info('Sent Newsletter');
    }
}
