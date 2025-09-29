<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
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
        if ($user->can('admin.role.index')) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.role.store')) {
            return true;
        }

        return false;
    }
    public function show(User $user): bool
    {
        if ($user->can('admin.role.show')) {
            return true;
        }

        return false;
    }
    public function update(User $user): bool
    {
        if ($user->can('admin.role.update')) {
            return true;
        }

        return false;
    }

    public function assignToUser(User $user): bool
    {
        if ($user->can('admin.role.assign_to_user')) {
            return true;
        }

        return false;
    }

    public function givePermission(User $user): bool
    {
        if ($user->can('admin.role.give_permission')) {
            return true;
        }

        return false;
    }
}
