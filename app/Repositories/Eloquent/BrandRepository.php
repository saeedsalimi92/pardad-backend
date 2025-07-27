<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\BrandRepositoryInterface;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function model(): string
    {
        return Brand::class;
    }
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->orderByDesc('priority')
            ->paginate($perPage);
    }

}
