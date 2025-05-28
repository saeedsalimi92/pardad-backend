<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function model(): string
    {
        return Comment::class;
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->with(['commentable.contents'])
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['mobile']), function ($query) use ($data) {
                $query->where('mobile', "LIKE", "%".$data["mobile"]."%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
