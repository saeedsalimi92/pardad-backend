<?php

namespace App\Repositories;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    public function getList($data): \Illuminate\Database\Eloquent\Collection;
}
