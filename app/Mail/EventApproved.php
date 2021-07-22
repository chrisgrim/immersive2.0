<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The ModeratorComment instance.
     *
     * @var ModeratorComment
     */
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
        return $this->from('submissions@everythingimmersive.com')
                    ->view('emails.events.event-approved')
                    ->with([
                        'name' => $this->event->name,
                        'image' => $this->event->largeImagePath,
                        'slug' => $this->event->slug,
                    ]);
    }
}
