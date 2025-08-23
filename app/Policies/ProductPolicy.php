<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function __construct()
    {
    }

    public function SuperIndex(User $user): bool
    {
        return $user->can('admin.product.super_index');
    }

    public function index(User $user): bool
    {
        return $user->can('admin.product.index');
    }

    public function SuperShow(User $user): bool
    {
        if ($user->can('admin.product.super_show')) {
            return true;
        }

        return false;
    }

    public function show(User $user, Product $product): bool
    {
        if ($user->can('admin.product.show') && $product->author_id == $user->id) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.product.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->can('admin.product.update') && $product->author_id == $user->id) {
            return true;
        }

        return false;
    }

    public function SuperUpdate(User $user): bool
    {
        if ($user->can('admin.product.super_update')) {
            return true;
        }

        return false;
    }

    public function SuperDestroy(User $user): bool
    {
        if ($user->can('admin.product.super_destroy')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user, Product $product): bool
    {
        if ($user->can('admin.product.destroy') && $product->author_id == $user->id) {
            return true;
        }

        return false;
    }

    public function change_status(User $user): bool
    {
        if ($user->can('admin.product.change_status')) {
            return true;
        }

        return false;
    }

    public function list(User $user): bool
    {
        if ($user->can('admin.product.list')) {
            return true;
        }

        return false;
    }
    public function statuses(User $user): bool
    {
        return $user->can('admin.product.super_statuses') || $user->can('admin.product.statuses');
    }






    public function bentoLayoutUpdate(User $user): bool
    {
        if ($user->can('admin.product.bento_layout_update')) {
            return true;
        }

        return false;
    }
}
