<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advisory extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
    	'wheelchairReady','sexual','sexualDescription','mobilityAdvisories','contactAdvisories','event_id','ageRestriction', 'audience'
    ];
    
    /**
     * Expect Model hasOne Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function event() 
    {
        return $this->hasOne(Event::class);
    }
}


