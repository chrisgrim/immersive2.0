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
        $messages = auth()->user()->conversations()->with('users')->paginate(10);
        return view('messages.index', compact('messages'));
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
        $conversation = $conversation->load('event');
        auth()->user()->update(['unread' => null]);
        return view('messages.show', compact('conversation'));
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

        $ModeratorComment = Message::create([
            'conversation_id' => $conversation->id,
            'message' => $request->message,
            'user_id' => auth()->id(),
        ]);
        $attributes = [
            'email' => $receiver ? $receiver->email : '',
            'receiver' => $receiver ? $receiver->name : '',
            'body' => $request->message,
            'name' => auth()->user()->name,
            'event' => $request->event ? $request->event['name'] : null
        ];

        if ($request->type == 'message') {
            $receiver ? $receiver->update(['unread' => 'm']) : '';
        } else {
            $receiver ? $receiver->update(['unread' => 'e']) : '';
        }

        $conversation->touch();

        $receiver ? Mail::to($receiver->email)->send(new NewMessage($attributes)) : '';
        
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
    public function fetcheventmessages()
    {
        return auth()->user()->eventconversations()->with('users', 'event')->paginate(10);
    }
}
