<?php

namespace App\Http\Controllers\Creation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class HostController extends Controller
{
    /**
     * Updates a model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        if ($request->newMember) {
            $user = User::where('email', '=' , $request->newMember)->first();
            if ($user === null) {
               return response()->json(['errors' => ['email' => ['No user exists with that email.']]], 422);
            }
            $event->organizer->users()->syncWithoutDetaching($user->id, ['role' => 'moderator']);
        }
        if ($request->removeMember) {
            $event->organizer->users()->detach($request->removeMember, ['role' => 'moderator']);
        }
        return $event->organizer->users->fresh();
    }
}