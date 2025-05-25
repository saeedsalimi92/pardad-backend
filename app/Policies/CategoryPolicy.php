<?php

namespace App\Policies;

use App\Models\User;

class CategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function index(User $user): bool
    {
        if ($user->can('admin.category.index')) {
            return true;
        }

        return false;
    }

    public function show(User $user): bool
    {
        if ($user->can('admin.category.show')) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.category.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user): bool
    {
        if ($user->can('admin.category.update')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user): bool
    {
        if ($user->can('admin.category.destroy')) {
            return true;
        }

        return false;
    }

    public function change_status(User $user): bool
    {
        if ($user->can('admin.category.change_status')) {
            return true;
        }

        return false;
    }

    public function list(User $user): bool
    {
        if ($user->can('admin.category.list')) {
            return true;
        }

        return false;
    }
}
