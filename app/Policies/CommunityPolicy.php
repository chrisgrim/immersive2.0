<?php

namespace App\Policies;

use App\Models\Curated\Community;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the community.
     *
     * @param  \App\User  $user
     * @param  \App\community  $community
     * @return mixed
     */
    public function update(User $user, Community $community)
    {
        return $community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }

    /**
     * Determine whether the user can see the community.
     *
     * @param  \App\User  $user
     * @param  \App\listing  $listing
     * @return mixed
     */
    public function preview(?User $user, Community $community)
    {
        if ($community->status === 'p') { 
            return true;
        } else {
            if ($user) {
                return $community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
            }
        }
    }

    /**
     * Determine whether the user can see the community.
     *
     * @param  \App\User  $user
     * @param  \App\listing  $listing
     * @return mixed
     */
    public function locked(?User $user, Community $community)
    {
        if ($community->status === 'p') { 
            if ($user) {
                return $community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
            }
        }
    }

    /**
     * Determine whether the user can delete the community.
     *
     * @param  \App\User  $user
     * @param  \App\community  $community
     * @return mixed
     */
    public function destroy(User $user, Community $community)
    {
        return $user->type == 'a' || $user->type == 'm';
    }

    /**
     * Determine whether the user can delete the community.
     *
     * @param  \App\User  $user
     * @param  \App\community  $community
     * @return mixed
     */
    public function owner(User $user, Community $community)
    {
        return $community->owner->id === $user->id || $user->type == 'a' || $user->type == 'm';
    }
}
