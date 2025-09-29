<?php

namespace App\Repositories\Eloquent;

use App\Models\Redirect;
use App\Repositories\BaseRepository;
use App\Repositories\RedirectRepositoryInterface;

class RedirectRepository extends BaseRepository implements RedirectRepositoryInterface
{
    public function model(): string
    {
        return Redirect::class;
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

}
