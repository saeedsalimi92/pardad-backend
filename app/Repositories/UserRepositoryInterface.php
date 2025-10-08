<?php

namespace App\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;


}
