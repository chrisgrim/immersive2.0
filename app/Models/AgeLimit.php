<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeLimit extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [];

    /**
     * Each genre has many Events
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function events() 
    {
        return $this->hasMany(Event::class);
    }
}
