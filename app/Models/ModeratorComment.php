<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeratorComment extends Model
{
    /**
    * What protected variables are allowed to be passed to the database
    *
    * @var array
    */
    protected $fillable = [
        'comments', 'event_id', 'conversation_id', 'user_id'
    ];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['user'];

    /**
    * Each comment belongs to one Event
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function event() 
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    /**
    * Each comment belongs to one Event
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * This Conversation has many event notices
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}