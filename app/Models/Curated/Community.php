<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    // protected $with = ['collections'];

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
}
