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

    public function show(User $user): bool
    {
        if ($user->can('admin.comment.show')) {
            return true;
        }

        return false;
    }

    public function index(User $user): bool
    {
        if ($user->can('admin.comment.index')) {
            return true;
        }

        return false;
    }

    public function changeStatus(User $user): bool
    {
        if ($user->can('admin.comment.change_status')) {
            return true;
        }

        return false;
    }

    public function reply(User $user): bool
    {
        if ($user->can('admin.comment.reply')) {
            return true;
        }

        return false;
    }
}
