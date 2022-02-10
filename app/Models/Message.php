<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'user_id', 'conversation_id','is_seen', 'deleted_from_sender','deleted_from_receiver'];

    /**
    * The relations to eager load on every query. I am adding shows here because I need to filter by dates for the search
    *
    * @var array
    */
    protected $with = ['user'];

    /**
     * This message belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * This message belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
    * Each message can belongs to one Event
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function event() 
    {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    /**
     * This message belongs to a conversation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */   
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public static function eventnotification($event, $status, $request) 
    {

        if($event->conversation()->count()) {
            $conversation = $event->conversation()->first();
            $conversation->touch();
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'message' => $status == 'denied' ? $request->message : $status,
                'user_id' => auth()->id(),
            ]);
        } else {
            $ids = [$event->user_id, auth()->id()];
            $conversation = Conversation::create([
                'event_id' => $event->id, 
                'user_one' => auth()->id(), 
                'user_two' => $event->user_id,
                'event_name' => $event->name
            ]);
            $conversation->users()->sync($ids);
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'message' => $status == 'denied' ? $request->message : $status,
                'user_id' => auth()->id(),
            ]);
        };

        $event->user->update(['unread' => 'e']);
        return $message;
    }
}
