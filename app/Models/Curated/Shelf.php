<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\QueryDsl;

class Shelf extends Model
{
    use HasFactory;
    use QueryDsl;
    use Searchable;

    protected $fillable = [ 'name', 'order', 'user_id', 'community_id', 'parent_id'];

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
     * Get the Community that owns the Shelf.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function postsWithCards()
    {
        return $this->hasMany(Post::class)->orderBy('order', 'ASC')->with('limitedCards')->limit(5);
    }

    public function publicPostsWithCards()
    {
        return $this->hasMany(Post::class)->where('status', 'p')->orderBy('order', 'ASC')->with('limitedCards')->limit(5);
    }
}