<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function create($data);

    public function update($id, $data): int;

    public function delete($id);

    public function findById($id, $with=[]);

    public function firstOrFail($id, $with=[]);

    public function filter();
}
