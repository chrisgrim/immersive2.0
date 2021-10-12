<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Featured\Feature;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;


class Shelf extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;
    use QueryDsl;
    use Searchable;

    protected $fillable = [ 'name', 'order', 'user_id', 'community_id', 'parent_id', 'status'];

    protected $with = ['community'];

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
    * Helpful command to see published listings
    *
    * @return bool
    */
    public function isPublished() {
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
            'order' => $this->order,
        ];
    }

    /**
     * Get the posts for the shelf .
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('order', 'ASC');
    }

    /**
     * Get the docks for the shelf .
     */
    public function docks()
    {
        return $this->morphToMany('\App\Models\Featured\Dock', 'association')->using('App\Models\Featured\Association');
    }

    /**
     * Get the Community that owns the Shelf.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Show the posts that are published
     */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class)->orderBy('order', 'ASC')->where('status', 'p')->limit(4);
    }

    /**
     * Get all of the communities featureds.
     */
    public function featured()
    {
        return $this->morphOne(Feature::class, 'featureable');
    }
}