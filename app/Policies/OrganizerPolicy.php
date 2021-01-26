<?php

namespace App\Policies;

use App\Models\Organizer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can edit the organizer.
     *
     * @param  \App\User  $user
     * @param  \App\Organizer  $Organizer
     * @return mixed
     */
    public function update(User $user, Organizer $organizer)
    {
        return $user->belongsToOrganization($organizer) || $user->type == 'a' || $user->type == 'm';
    }
}
