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
            ->with([
                'contents' => function($query) use ($data) {
                    $query
                        ->when(isset($data['locale']), function ($q) use ($data) {
                            $q->where('locale', $data["locale"]);
                        })
                        ->when(isset($data['title']), function ($q) use ($data) {
                            $q->where('title', 'LIKE', "%".$data["title"]."%");
                        });
                },
                'parent',
                'parent.contents' => function($query) use ($data) {
                    $query->when(isset($data['locale']), function ($q) use ($data) {
                        $q->where('locale', $data["locale"]);
                    });
                },
                'children',
                'children.contents' => function($query) use ($data) {
                    $query->when(isset($data['locale']), function ($q) use ($data) {
                        $q->where('locale', $data["locale"]);
                    });
                }
            ])
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['type']) && $data['type'] !== 'all', function ($query) use ($data) {
                // اگه type مشخص شده و all نباشه
                $query->where('type', $data['type']);
            }, function ($query) {
                // حالت پیش‌فرض: فقط normal
                $query->where('type', 'normal');
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
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->with([
                'contents' => function($query) use ($data) {
                    $query
                        ->when(isset($data['locale']), function ($query) use ($data) {
                            $query->where('locale', $data["locale"]);
                        })
                        ->when(isset($data['title']), function ($query) use ($data) {
                            $query->where('title', "LIKE", "%".$data["title"]."%");
                        });
                },
                'parent',
                'parent.contents' => function($query) use ($data) {
                    $query->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    });
                },
                'children',
                'children.contents' => function($query) use ($data) {
                    $query->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    });
                }
            ])
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['type']) && $data['type'] !== 'all', function ($query) use ($data) {
                // فیلتر بر اساس type خود دسته‌بندی
                $query->where('type', $data['type']);
            }, function ($query) {
                // حالت پیش‌فرض: فقط normal
                $query->where('type', 'normal');
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
            ->when(isset($data['category_type']), function ($query) use ($data) {
                // فیلتر بر اساس type دسته‌بندی والد (اگر نیاز باشد)
                $query->whereHas('parent', function ($q) use ($data) {
                    $q->where('type', $data['category_type']);
                });
            })
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->get();
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
            }, 'children', 'children.contents' => function($query) use ($data) {
                    $query->when(isset($data['locale']), function ($query) use ($data) {
                        $query->where('locale', $data["locale"]);
                    });
            }])
            ->first();
    }
    public function findBySlug(string $slug, string $locale = 'fa')
    {
        return $this->query()
            ->whereHas('contents', function ($query) use ($slug, $locale) {
                $query->where('slug', $slug)
                    ->where('locale', $locale);
            })
            ->with([
                'contents' => fn($q) => $q->where('locale', $locale),
                'children',
                'children.contents' => fn($q) => $q->where('locale', $locale)
            ])
            ->first();
    }

}
