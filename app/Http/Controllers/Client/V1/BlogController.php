<?php

namespace App\Http\Controllers\Client\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Admin\V1\Blog\BlogListRequest;
use App\Http\Requests\Admin\V1\Blog\BlogShowRequest;
use App\Http\Requests\Client\V1\Blog\BlogListByParentRequest;
use App\Models\Blog;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }

    public function index()
    {
    }

    public function show()
    {
    }

}
