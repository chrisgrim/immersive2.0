<?php

namespace App\Models\Curated;

use App\Models\MakeImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Card extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the Collection that owns the Card.
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
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
                MakeImage::deleteImage($card);
            }
        }
        $card->delete();
    }
}
