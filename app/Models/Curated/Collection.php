<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $guarded = [];

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
        return $this->hasMany(Card::class);
    }
}
