<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceRange extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
        'price'
    ];

    /**
     * Each Price belongs to one event
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function event() 
    {
        return $this->belongsTo(Event::class);
    }
}
