<?php

namespace App\Policies;

use App\Models\Curated\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        return $post->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }

    /**
     * Determine whether the user can see the post.
     *
     * @param  \App\User  $user
     * @param  \App\post  $post
     * @return mixed
     */
    public function preview(?User $user, Post $post)
    {
        if ($post->status !== 'p') {
            if ($user) {
                return $post->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\post  $post
     * @return mixed
     */
    public function destroy(User $user, Post $post)
    {
        return $post->community->curators->contains('id', $user->id) || $user->type == 'a' || $user->type == 'm';
    }
}
