<?php

namespace App\Repositories;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    public function findByLocale($code, $data);

    public function findByCode($code, $with=[]);

    public function getList($data): \Illuminate\Database\Eloquent\Collection;

    public function getPublishedProductsPaginated($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function getBlogsByProductCode($code,$data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
