<?php

namespace App\Repositories\Eloquent;

use App\Enums\BlogStatus;
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
            ->when(isset($data['type']), function ($query) use ($data) {
                // استفاده از whereHas برای فیلتر بر اساس type دسته‌بندی
                $query->whereHas('category', function ($q) use ($data) {
                    $q->where('type', $data['type']);
                });
            })
            ->with([
                'contents' => function($query) use ($data) {
                    $query
                        ->when(isset($data['locale']), function ($q) use ($data) {
                            $q->where('locale', $data["locale"]);
                        })
                        ->when(isset($data['title']), function ($q) use ($data) {
                            $q->where('title', "LIKE", "%".$data["title"]."%");
                        });
                },
                'category' => function($query) use ($data) {
                    $query->with(['contents' => function($q) use ($data) {
                        $q->when(isset($data['locale']), function ($q) use ($data) {
                            $q->where('locale', $data["locale"]);
                        });
                    }]);
                },
                'category.contents' // اضافه کردن محتوای دسته‌بندی
            ])
            ->when(isset($data['user_id']), function ($query) use ($data) {
                $query->where('author_id', $data['user_id']);
            })
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['category_id']), function ($query) use ($data) {
                $query->where('category_id', $data["category_id"]);
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
            }, 'relatedBlogs' => function ($query) use ($data) {
                $query
                    ->where('status', BlogStatus::Published->value)
                    ->where("published_at", "<=", now());
            }, 'faqs' => function ($query) use ($data) {
                $query->select(["id","faqable_id", "faqable_type", "question", "answer"]);
            }])
            ->first();
    }

    public function getPublishedBlogsPaginated($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->select([
                "id",
                "code",
                "category_id",
                "status",
                "created_at",
                "updated_at",
                "published_at",
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
            ->where('status', BlogStatus::Published->value)
            ->where("published_at", "<=", now())
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->with(['contents' => function($query) use ($data) {
                $query
                    ->select('id', 'blog_id', 'title')
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
            ->when(isset($data['type']), function ($query) use ($data) {
                // فیلتر بر اساس type دسته‌بندی
                $query->whereHas('category', function ($q) use ($data) {
                    $q->where('type', $data['type']);
                });
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
