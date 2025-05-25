<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function changeStatus(User $user): bool
    {
        if ($user->can('admin.comment.change_status')) {
            return true;
        }

        return false;
    }
}
