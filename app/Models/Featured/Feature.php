<?php

namespace App\Models\Featured;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Curated\Post;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'type' ];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['featureable'];

    /**
     * Get the section that has this featured
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    /**
     * Get the Dock that has this featured
     */
    public function docks()
    {
        return $this->belongsToMany(Dock::class);
    }

    /**
     * Get the model that has this featured
     */
    public function featureable()
    {
        return $this->morphTo();
    }
}
