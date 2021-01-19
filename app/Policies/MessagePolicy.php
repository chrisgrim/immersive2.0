<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can edit the organizer.
     *
     * @param  \App\User  $user
     * @param  \App\Message  $Message
     * @return mixed
     */
    public function update(User $user, Message $Message)
    {
        return $message->user_id == $user->id || $user->type == 'a' || $user->type == 'm';
    }
}
