<?php

namespace App\Policies;

use App\Models\User;

class ActivityLogPolicy
{
    public function __construct() {}

    public function index(User $user): bool
    {
        if ($user->can('admin.activity_log.index')) {
            return true;
        }

        return false;
    }
}
