<?php

namespace App\Policies;

use App\Models\Curated\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the listing.
     *
     * @param  \App\User  $user
     * @param  \App\listing  $listing
     * @return mixed
     */
    public function update(User $user, Listing $listing)
    {
        return $listing->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }

    /**
     * Determine whether the user can delete the listing.
     *
     * @param  \App\User  $user
     * @param  \App\listing  $listing
     * @return mixed
     */
    public function delete(User $user, Listing $listing)
    {
        return $listing->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }
}
