<?php

namespace App\Services;

use App\Repositories\ActivityLogRepositoryInterface;

readonly class ActivityLogService
{
    public function __construct(private ActivityLogRepositoryInterface $activityLogRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->activityLogRepository->index($data, $perPage);
    }
}
