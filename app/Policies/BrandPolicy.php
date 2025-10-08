<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BrandPolicy
{
    /**
     * Determine whether the user can view any models.
     */
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
    public function change_priority(User $user): bool
    {
        if ($user->can('admin.brand.change_priority')) {
            return true;
        }
        return false;
    }
}
