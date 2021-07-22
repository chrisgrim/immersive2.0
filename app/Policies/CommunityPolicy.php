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
     * Determine whether the user can delete the community.
     *
     * @param  \App\User  $user
     * @param  \App\community  $community
     * @return mixed
     */
    public function delete(User $user, Community $community)
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
