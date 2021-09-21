<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ImageFile;
use App\Models\User;
use App\Models\Featured\Feature;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;
use App\Models\Featured\Section;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;
    use QueryDsl;
    use Searchable;

    protected $fillable = [ 'name', 'user_id', 'slug', 'blurb', 'thumbImagePath', 'largeImagePath', 'instagramHandle', 'twitterHandle', 'facebookHandle', 'patreon', 'status' ];

    /**
    * What events should be searchable for scout elastic search
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function shouldBeSearchable()
    {
        return $this->status === 'p';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
        ];
    }

    /**
     * Delete any listings with the community
     */
    public static function boot() {
        parent::boot();
        self::deleting(function($community) { 
            $community->listings()->each(function($listing) {
                ImageFile::deletePreviousImages($listing);
                $listing->delete();
            });
        });
    }

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
     * Get the listings for the community .
     */
    public function listings()
    {
        return $this->hasMany(Listing::class)->orderBy('order', 'ASC');
    }

    /**
     * Returns limited listings for the community .
     */
    public function limitedListings()
    {
        return $this->hasMany(Listing::class)->orderBy('order', 'ASC')->limit(3);
    }

    /**
     * Returns community Shelves.
     */
    public function shelves()
    {
        return $this->hasMany(Shelf::class)->orderBy('order', 'ASC');
    }

    /**
     * Returns community Shelves.
     */
    public function limitedShelves()
    {
        return $this->hasMany(Shelf::class)->orderBy('order', 'ASC')->limit(3);
    }

    /**
     * Get the owner of the community .
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the curators for the community .
     */
    public function curators()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get all of the listings featureds.
     */
    public function featured()
    {
        return $this->morphOne(Feature::class, 'featureable');
    }

}
