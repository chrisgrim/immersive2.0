<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'blurb', 'thumbImagePath', 'shelf_id', 'largeImagePath', 'user_id', 'community_id', 'status', 'order' ];

    /**
    * Sets the Route Key to slug instead of ID
    *
    * @return Route Key Name
    */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
    * Helpful command to see published listings
    *
    * @return bool
    */
    public function isPublished() {
        return $this->status == 'p';
    }

    /**
     * Get the Community that owns the Listing.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the Shelf that owns the Listing.
     */
    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    /**
     * Get the user that owns the Listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cards for the collection .
     */
    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('order', 'ASC');
    }

    /**
     * Get the cards for the collection .
     */
    public function limitedCards()
    {
        return $this->hasMany(Card::class)->orderBy('order', 'ASC');
        // return $this->hasMany(Card::class)->orderBy('order', 'ASC')->limit(4);
    }

    /**
     * Delete any listings with the listing
     */
    public static function boot() {
        parent::boot();
        self::deleting(function($listing) { 
            $listing->cards()->each(function($card) {
                $card->destroyCard($card);
            });
        });
    }
}
