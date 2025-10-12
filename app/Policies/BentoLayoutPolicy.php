<?php

namespace App\Policies;

use App\Models\User;

class BentoLayoutPolicy
{
    public function __construct()
    {
    }

    public function index(User $user): bool
    {
        if ($user->can('admin.bento_layout.index')) {
            return true;
        }

        return false;
    }

    public function show(User $user): bool
    {
        if ($user->can('admin.bento_layout.show')) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.bento_layout.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user): bool
    {
        if ($user->can('admin.bento_layout.update')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user): bool
    {
        if ($user->can('admin.bento_layout.destroy')) {
            return true;
        }

        return false;
    }
}
