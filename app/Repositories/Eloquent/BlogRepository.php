<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\BaseRepository;
use App\Repositories\BlogRepositoryInterface;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    public function model(): string
    {
        return Blog::class;
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->with(['contents'])
            ->when(isset($data['title']), function ($query) use ($data) {
                $query->where('title', 'like', '%' . $data['title'] . '%');
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data['status']);
            })
            ->when(isset($data['parent_id']), function ($query) use ($data) {
                $query->where('parent_id', $data['parent_id']);
            })
            ->when(isset($data['parent_type']), function ($query) use ($data) {
                $query->where('parent_type', $data['parent_type']);
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->when(isset($data['title']), function ($query) use ($data) {
                $query->where('title', 'like', '%' . $data['title'] . '%');
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data['status']);
            })
            ->when(isset($data['parent_id']), function ($query) use ($data) {
                $query->where('parent_id', $data['parent_id']);
            })
            ->when(isset($data['parent_type']), function ($query) use ($data) {
                $query->where('parent_type', $data['parent_type']);
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
