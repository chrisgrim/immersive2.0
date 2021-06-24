<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ImageFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'blurb', 'thumbImagePath', 'largeImagePath', 'instagramHandle', 'twitterHandle', 'facebookHandle', 'patreon' ];

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
        return $this->hasMany(Listing::class);
    }

    /**
     * Get the collections for the community .
     */
    public function curators()
    {
        return $this->belongsToMany(User::class);
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

}
