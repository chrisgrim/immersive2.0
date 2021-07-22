<?php

namespace App\Mail\Curated;

use Illuminate\Bus\Queueable;
use App\Models\Curated\Community;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunityRejected extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The community instance.
     *
     * @var community
     */
    protected $community;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, Community $community)
    {
        $this->message = $message;
        $this->community = $community;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('submissions@everythingimmersive.com')
                    ->view('emails.curated.community-rejected')
                    ->with([
                        'name' => $this->community->name,
                        'comments' => $this->message,
                    ]);
    }

}
