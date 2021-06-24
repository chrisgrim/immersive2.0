<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'blurb', 'thumbImagePath', 'largeImagePath', 'user_id', 'community_id', 'status', 'order' ];

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
     * Get the Community that owns the Collection.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Get the cards for the collection .
     */
    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('order', 'ASC');;
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
