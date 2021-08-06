<?php

namespace App\Policies;

use App\Models\Curated\Card;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the card.
     *
     * @param  \App\User  $user
     * @param  \App\card  $card
     * @return mixed
     */
    public function update(User $user, Card $card)
    {
        return $card->listing->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }

    /**
     * Determine whether the user can destroy the card.
     *
     * @param  \App\User  $user
     * @param  \App\card  $card
     * @return mixed
     */
    public function destroy(User $user, Card $card)
    {
        return $card->listing->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }
}
