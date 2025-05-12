<?php

namespace App\Services;

use App\Repositories\BlogRepositoryInterface;

readonly class BlogService
{
    public function __construct(private BlogRepositoryInterface $blogRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->blogRepository->index($data, $perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->blogRepository->getList($data);
    }

    public function create($data)
    {
        $data['url'] = generateUrl($data['url'] ?? null, $data['title']);

        if (empty($data['canonical'])) {
            $data['canonical'] = generateSlug($data['url']);
        }

        $blog = $this->blogRepository->create($data);

        if($data["related_blog_ids"]) {
            $blog->relatedBlogs()->sync($data["related_blog_ids"]);
        }

        return $blog;
    }

    public function update($blog, $data): int
    {
        $data['url'] = generateUrl($data['url'] ?? null, $data['title']);

        if (empty($data['canonical'])) {
            $data['canonical'] = generateSlug($data['url']);
        }

        $this->blogRepository->update($blog->id, $data);

        if($data["related_blog_ids"]) {
            $blog->relatedBlogs()->sync($data["related_blog_ids"]);
        }

        return $blog;
    }

    public function destroy($blog)
    {
        foreach ($blog->images as $image) {
            $blog->deleteImage($image->id);
        }

        return $blog->delete();
    }
}
