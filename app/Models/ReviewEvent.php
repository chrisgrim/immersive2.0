<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReviewEvent;

class ReviewEvent extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
        'user_id','event_id','organizer_id','image_path','reviewer_name','url','review','rank'
    ];

    /**
     * Each review belongs to an event
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function event() 
    {
        return $this->belongsTo(Event::class);
    }

    public static function saveReviewEvent($request)
    {
        ReviewEvent::create([
            'user_id' => auth()->id(),
            'event_id' => $request->event['id'],
            'organizer_id' => $request->event['organizer_id'],
            'reviewer_name' => $request->reviewername,
            'url' => $request->url,
            'review' => $request->review,
            'image_path' => $request->image_path,
            'rank' => $request->rank ? $request->rank : 5
        ]);
    }

}
