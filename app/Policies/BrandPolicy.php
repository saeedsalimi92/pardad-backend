<?php

namespace App\Policies;

use App\Models\User;

class BrandPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function index(User $user): bool
    {
        if ($user->can('admin.brand.index')) {
            return true;
        }

        return false;
    }

    public function show(User $user): bool
    {
        if ($user->can('admin.brand.show')) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.brand.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user): bool
    {
        if ($user->can('admin.brand.update')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user): bool
    {
        if ($user->can('admin.brand.destroy')) {
            return true;
        }

        return false;
    }

    public function change_status(User $user): bool
    {
        if ($user->can('admin.brand.change_status')) {
            return true;
        }

        return false;
    }

    public function list(User $user): bool
    {
        if ($user->can('admin.brand.list')) {
            return true;
        }

        return false;
    }
}
