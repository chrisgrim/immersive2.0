<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Featured\Feature;
use App\Models\Featured\Section;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'blurb', 'thumbImagePath', 'largeImagePath', 'user_id', 'community_id', 'section_id', 'status', 'order' ];

    protected $with = ['limitedCards'];

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
     * Get the Section that owns the Listing.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get all of the listings featureds.
     */
    public function featured()
    {
        return $this->morphOne(Feature::class, 'featureable');
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
            $featured = $listing->featured()->first();
            $ids = $featured->sections->map(function($section) { return $section->id; });
            $featured->sections()->detach($ids);
            $featured->delete();
        });
    }
}
