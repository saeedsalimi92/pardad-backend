<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;

readonly class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }
}
