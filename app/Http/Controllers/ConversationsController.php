<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Event;
use App\Models\ModeratorComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMessage;
use App\Mail\Newsletter;
use Carbon\Carbon;

class ConversationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $convos = Conversation::all();
        // foreach ($convos as $convo) {
        //     if ($convo->event) {
        //         $convo->update([
        //             'event_name' => $convo->event->name
        //         ]);
        //     }
        // }
        // return 'finished';
        $events = auth()->user()->eventconversations()->limit(20)->get();
        return view('messages.index', compact('events'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {
        $this->authorize('update', $conversation);
        auth()->user()->update(['unread' => null]);
        return $conversation->load('event', 'messages');
        // return view('messages.show', compact('conversation'));
    }

    /**

     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organizer  $organizer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversation $conversation)
    {   
        $this->authorize('update', $conversation);
        $receiver = $conversation->users->where('id', '!=' , auth()->id())->first();
        $latest = $conversation->latestMessages()->first();
        $start = $latest->created_at;
        $end = Carbon::parse($latest->created_at)->addMinutes(1);

        if ( Carbon::now()->between($start, $end) && $latest->user->id === auth()->id() ) {
            $latest->update([
                'message' => $latest->message . $request->message
            ]);
        } else {
            Message::create([
                'conversation_id' => $conversation->id,
                'message' => $request->message,
                'user_id' => auth()->id(),
            ]);
        }

        $attributes = [
            'email' => $receiver ? $receiver->email : '',
            'receiver' => $receiver ? $receiver->name : '',
            'body' => $request->message,
            'name' => auth()->user()->name,
            'event' => $request->event ? $request->event['name'] : null
        ];

        if (!$receiver->unread) {
             // Let the receiver know they have an unread message
            $receiver ? $receiver->update(['unread' => 'm']) : '';
            // send an email to the reciever
            $receiver ? Mail::to($receiver->email)->send(new NewMessage($attributes)) : '';
        }

        //update the conversation
        $conversation->touch();
        return $conversation->load('event', 'messages');
        
    }

    /**
     * Get Messages From Database
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchmessages()
    {
        return auth()->user()->conversations()->with('users')->paginate(10);
    }

    /**
     * Get Messages From Database
     *
     * @return \Illuminate\Http\Response
     */
    public function fetcheventmessages(Request $request)
    {
        if (! $request->search) return auth()->user()->eventconversations()->limit(20)->get();

        return auth()->user()->eventconversations()->where('event_name', 'LIKE', "%$request->search%")->limit(10)->get();
    }
}
