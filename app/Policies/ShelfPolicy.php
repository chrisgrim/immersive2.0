<?php

namespace App\Policies;

use App\Models\Curated\Shelf;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShelfPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the shelf.
     *
     * @param  \App\User  $user
     * @param  \App\card  $card
     * @return mixed
     */
    public function update(User $user, Shelf $shelf)
    {
        return $shelf->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }

    /**
     * Determine whether the user can destroy the shelf.
     *
     * @param  \App\User  $user
     * @param  \App\card  $card
     * @return mixed
     */
    public function destroy(User $user, Shelf $shelf)
    {
        return $shelf->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }
}
