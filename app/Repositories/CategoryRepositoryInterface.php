<?php

namespace App\Repositories;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    public function getList($data): \Illuminate\Database\Eloquent\Collection;

    public function findByLocale($code, $data);
}
