<?php

namespace App\Ticsol\Base\Policies;

use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        //
    }

    /**
     * Determine whether the user can view the list of jobs.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    function list(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the comment.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Comment  $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        return $comment->client_id == $user->client_id && $comment->creator_id == $user->id;
    }

    /**
     * Determine whether the user can create comments.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $comment->client_id == $user->client_id && $comment->creator_id == $user->id;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  \App\Ticsol\Components\Models\User  $user
     * @param  \App\Ticsol\Components\Models\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $comment->client_id == $user->client_id && $comment->creator_id == $user->id;
    }
}
