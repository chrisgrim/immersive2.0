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

    protected $fillable = [ 'name', 'user_id', 'slug', 'blurb', 'description', 'thumbImagePath', 'largeImagePath', 'instagramHandle', 'twitterHandle', 'facebookHandle', 'patreon', 'status' ];

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
     * Delete any posts with the community
     */
    public static function boot() {
        parent::boot();
        self::deleting(function($community) { 
            $community->posts()->each(function($post) {
                ImageFile::deletePreviousImages($post);
                $post->delete();
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
     * Get the posts for the community .
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('order', 'ASC');
    }

    /**
     * Get the Docks of the Communities.
     */
    public function docks()
    {
        return $this->morphToMany('\App\Models\Featured\Dock', 'association')->using('App\Models\Featured\Association');
    }

    /**
     * Returns limited posts for the community .
     */
    public function limitedPosts()
    {
        return $this->hasMany(Post::class)->orderBy('order', 'ASC')->limit(3);
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
    public function publishedShelves()
    {
        return $this->hasMany(Shelf::class)->orderBy('order', 'ASC')->where('status', 'p');
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
     * Get all of the communities featureds.
     */
    public function featured()
    {
        return $this->morphOne(Feature::class, 'featureable');
    }

}
