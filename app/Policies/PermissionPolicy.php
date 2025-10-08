<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(User $user): bool
    {
        if ($user->can('admin.permission.index')) {
            return true;
        }

        return false;
    }
}
