<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = ['opener_id', 'receiver_id', 'event_id','user_one','user_two'];
    
    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['messages'];

    /**
     * This Conversation has many Messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('updated_at', 'DESC');
    }

    /**
     * This Conversation belongs to an Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /*
     * make a relation between first user from conversation
     *
     * return collection
     * */
    public function userone()
    {
        return $this->belongsTo('App\User',  'user_one');
    }

    /*
     * make a relation between first user from conversation
     *
     * return collection
     * */
    public function usertwo()
    {
        return $this->belongsTo('App\User',  'user_two');
    }

    /**
     * This Conversation hasMany
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}