<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Event;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRenew extends Mailable
{
    use Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event-renew')
                    ->from('submissions@everythingimmersive.com')
                    ->subject('Add more dates to your event on Everything Immersive')
                    ->with([
                        'event' => $this->event,
                    ]);
    }
}
