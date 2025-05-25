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
            ->with(['contents' => function($query) use ($data) {
                $query
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
            ->when(isset($data['title']), function ($query) use ($data) {
                $query->where('title', "LIKE", "%".$data["title"]."%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findByLocale($id, $data)
    {
        return $this->query()
            ->where("id", $id)
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
                $query->select(["id", "question", "answer"]);
            }])
            ->first();
    }

    public function getPublishedBlogsPaginated($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
            }])
            ->when(isset($data['category_id']), function ($query) use ($data) {
                $query->where('category_id', $data["category_id"]);
            })
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
