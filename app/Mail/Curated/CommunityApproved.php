<?php

namespace App\Mail\Curated;

use Illuminate\Bus\Queueable;
use App\Models\Curated\Community;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunityApproved extends Mailable
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
    public function __construct(Community $community)
    {
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
                    ->view('emails.curated.community-approved')
                    ->with([
                        'name' => $this->community->name,
                        'image' => $this->community->largeImagePath,
                        'slug' => $this->community->slug,
                    ]);
    }
}
