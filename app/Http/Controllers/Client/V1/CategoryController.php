<?php

namespace App\Http\Controllers\Client\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\V1\Category\CategoryIndexRequest;
use App\Http\Requests\Client\V1\Category\CategoryShowRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function index(CategoryIndexRequest $request)
    {

    }

    public function show(CategoryShowRequest $request)
    {
    }
}
