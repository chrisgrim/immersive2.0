<?php

namespace App\Models\Curated;

use App\Models\ImageFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Illuminate\Support\Str;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'blurb', 'url', 'thumbImagePath', 'order', 'post_id', 'event_id' ];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['event'];

    /**
     * Get the post that owns the Card.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Card belongs to an event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
    * Deletes the card images and then deletes card
    *
    * @return Nothing
    */
    public function destroyCard($card) 
    {
        if ($card->thumbImagePath) {
            if (!Str::contains($card->thumbImagePath, 'event-images')) { 
                ImageFile::deletePreviousImages($card);
            }
        }
        $card->delete();
    }
}
