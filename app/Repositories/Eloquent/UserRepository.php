<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model(): string
    {
        return User::class;
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
