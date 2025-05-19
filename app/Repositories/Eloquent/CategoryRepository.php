<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function model(): string
    {
        return Category::class;
    }
    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    })
                    ->when(isset($data['title']), function ($query) use ($data) {
                        $query->where('title', "LIKE", "%".$data["title"]."%");
                    });
            }, 'parent', 'parent.contents' => function($query) use ($data) {
                $query->when(isset($data['locale']), function ($query) use ($data) {
                    $query->where('locale', $data["locale"]);
                });
            }])
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['parent_id']), function ($query) use ($data) {
                $query->where('parent_id', $data["parent_id"]);
            })
            ->when(isset($data['scope']), function ($query) use ($data) {
                match ($data['scope']) {
                    'main' => $query->whereNull('parent_id'),
                    'children' => $query->whereNotNull('parent_id'),
                    default => null,
                };
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->select(["id", "category_id", "title"])
                    ->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    })
                    ->when(isset($data['title']), function ($query) use ($data) {
                        $query->where('title', "LIKE", "%".$data["title"]."%");
                    });
            }])
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['parent_id']), function ($query) use ($data) {
                $query->where('parent_id', $data["parent_id"]);
            })
            ->when(isset($data['scope']), function ($query) use ($data) {
                match ($data['scope']) {
                    'main' => $query->whereNull('parent_id'),
                    'children' => $query->whereNotNull('parent_id'),
                    default => null,
                };
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
