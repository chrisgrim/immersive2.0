<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventChanges extends Mailable
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
        $this->Event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('submissions@everythingimmersive.com')
                    ->view('emails.moderator-comments')
                    ->with([
                        'comments' => $this->Message->message,
                        'eventname' => $this->Event->name,
                    ]);
    }
}
