<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
        'home','street','city','region','country','postal_code','longitude','latitude','user_id'
    ];

    /**
     * Each Location belongs to an User Model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
