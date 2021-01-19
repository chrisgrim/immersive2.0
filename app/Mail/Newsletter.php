<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The event instance
     *
     * @var Event
     */
    protected $events;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\Illuminate\Database\Eloquent\Collection $events)

    {
        $this->events = $events;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('newsletter@everythingimmersive.com')
                    ->view('emails.newsletter')
                    ->with([
                        'events' => $this->events,
                    ]);
    }
}
