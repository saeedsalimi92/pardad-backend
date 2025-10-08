<?php

namespace App\Repositories;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
