<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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
        if ($user->can('admin.user.index')) {
            return true;
        }

        return false;
    }


    public function show(User $user): bool
    {
        if ($user->can('admin.user.show')) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.user.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user): bool
    {
        if ($user->can('admin.user.update')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user): bool
    {
        if ($user->can('admin.user.destroy')) {
            return true;
        }

        return false;
    }

    public function change_status(User $user): bool
    {
        if ($user->can('admin.user.change_status')) {
            return true;
        }

        return false;
    }
    public function change_password(User $user): bool
    {
        if ($user->can('admin.user.change_password')) {
            return true;
        }

        return false;
    }
}
