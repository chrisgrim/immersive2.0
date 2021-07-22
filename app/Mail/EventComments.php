<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventComments extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Message instance.
     *
     * @var ModeratorComment
     */
    protected $Message, $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $Message, Event $event)
    {
        $this->Message = $Message;
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
                    ->view('emails.events.event-comments')
                    ->with([
                        'image' => $this->event->largeImagePath,
                        'comments' => $this->Message->message,
                        'name' => $this->event->name,
                    ]);
    }
}
