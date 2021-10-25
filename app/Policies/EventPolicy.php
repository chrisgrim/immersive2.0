<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $event->owners()->contains('id', $user->id) && $event->status !== 'r' || $user->type == 'a' || $user->type == 'm';
    }


    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function finalize(User $user, Event $event)
    {
        return $event->status != 'p' && $event->status != 'r' && $event->status != 'e' && $event->user_id == $user->id && $event->status >= 8 || $user->type == 'a' || $event->status == 'n';
    }
}
