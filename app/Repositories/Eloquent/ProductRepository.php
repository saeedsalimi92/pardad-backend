<?php

namespace App\Repositories\Eloquent;

use App\Enums\Status;
use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function model(): string
    {
        return Product::class;
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->when(isset($data['type']), function ($query) use ($data) {
                $query->whereHas('category', function ($q) use ($data) {
                    $q->where('type', $data['type']);
                });
            })
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    })
                    ->when(isset($data['title']), function ($query) use ($data) {
                        $query->where('title', "LIKE", "%".$data["title"]."%");
                    });
            }, 'category' => function($query) use ($data) {
                $query
                    ->when(isset($data['category_id']), function ($query) use ($data) {
                        $query->where('id', $data["category_id"]);
                    });
            }])
            ->when(isset($data['user_id']), function ($query) use ($data) {
                $query->where('author_id', $data['user_id']);
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['title']), function ($query) use ($data) {
                $query->where('title', "LIKE", "%".$data["title"]."%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findByCode($code, $with = [])
    {
        return $this->query()
            ->where('code', $code)
            ->with($with)
            ->first();
    }

    public function findByLocale($code, $data)
    {
        return $this->query()
            ->where("code", $code)
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    });
            }])
            ->first();
    }

    public function getPublishedProductsPaginated($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->select([
                "id",
                "code",
                "category_id",
                "status",
                "created_at",
                "updated_at",
            ])
            ->whereHas("category", function ($query) use ($data) {
                $query->when(isset($data['category_id']), function ($query) use ($data) {
                    $query->where('code', $data["category_id"]);
                });
            })
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    })
                    ->when(isset($data['title']), function ($query) use ($data) {
                        $query->where('title', "LIKE", "%".$data["title"]."%");
                    });
            }])
            ->where('status', Status::Active->value)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->select('id', 'blog_id' ,'title')
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
            ->when(isset($data['category_id']), function ($query) use ($data) {
                $query->where('category_id', $data["category_id"]);
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
