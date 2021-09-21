<?php

namespace App\Models\Featured;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curated\Post;
use App\Models\Event;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'name', 'order' ];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    // protected $with = ['featured'];

    /**
     * Get the featured for the section .
     */
    public function featured()
    {
        return $this->belongsToMany(Feature::class)->withPivot('order')->orderBy('order', 'ASC');
    }

    /**
     * Get the live Featured for the section .
     */
    public function publicFeatured()
    {
        return $this->belongsToMany(Feature::class)->withPivot('order')->orderBy('order', 'ASC')->whereHasMorph(
            'featureable',
            [Post::class, Event::class],
            function ($query) {
                $query->where('status', 'like', 'p');
            }
        );
    }

    /**
     * Get the parent commentable model (post or video).
     */
    public function container()
    {
        return $this->morphTo();
    }

}
