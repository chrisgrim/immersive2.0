<?php

namespace App\Models\Curated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'order'];

    /**
     * Get the listings for the community .
     */
    public function listings()
    {
        return $this->hasMany(Listing::class)->orderBy('order', 'ASC');
    }

    /**
     * Get the Community that owns the Shelf.
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function listingsWithCards()
    {
        return $this->hasMany(Listing::class)->orderBy('order', 'ASC')->with('limitedCards:id,listing_id,thumbImagePath')->limit(5);
    }

    public function publicListingsWithCards()
    {
        return $this->hasMany(Listing::class)->where('status', 'p')->orderBy('order', 'ASC')->with('limitedCards:id,listing_id,thumbImagePath')->limit(4);
    }

}
