<?php

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\Response;

class RedirectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): bool
    {
        if ($user->can('admin.redirect.index')) {
            return true;
        }

        return false;
    }

    public function show(User $user): bool
    {
        if ($user->can('admin.redirect.show')) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.redirect.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user): bool
    {
        if ($user->can('admin.redirect.update')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user): bool
    {
        if ($user->can('admin.redirect.destroy')) {
            return true;
        }

        return false;
    }

    public function change_status(User $user): bool
    {
        if ($user->can('admin.redirect.change_status')) {
            return true;
        }

        return false;
    }
}
