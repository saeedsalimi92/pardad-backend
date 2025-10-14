<?php

namespace App\Repositories\Eloquent;

use App\Models\ActivityLog;
use App\Repositories\ActivityLogRepositoryInterface;
use App\Repositories\BaseRepository;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    public function model(): string
    {
        return ActivityLog::class;
    }
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
