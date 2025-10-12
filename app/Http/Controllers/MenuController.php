<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenu()
    {
        $categories = Category::select('id', 'title', 'url')->get();

        $mainItems = [
            [
                'title' => 'صفحات اصلی',
                'id' => 0,
                'type' => 'main',
                'icon' => null,
                'children' => [
                    [
                        'title' => 'صفحه 1',
                        'id' => 0,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],
                    [
                        'title' => 'صفحه 2',
                        'id' => 1,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],
                    [
                        'title' => 'صفحه 3',
                        'id' => 2,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],[
                        'title' => 'صفحه 4',
                        'id' => 3,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],[
                        'title' => 'صفحه 5',
                        'id' => 4,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],[
                        'title' => 'تماس با ما',
                        'id' => 5,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],[
                        'title' => 'درباره ما',
                        'id' => 6,
                        'type' => 'dashboard',
                        'icon' => null,
                        'children' => [],
                        'isActive' => false,
                        'route'=> null,
                        'path' => null,
                    ],
                ],
                'isActive' => false,
                'route'=> null,
                'path' => null
            ],
            [
                'title' => 'وبلاگ پرداد',
                'id' => 1,
                'type' => 'category',
                'icon' => null,
                'children' => [],
                'isActive' => false,
                'route'=> null,
                'path' => null,
            ],
            [
                'title' => 'لیست بلاگ ها',
                'id' => 2,
                'type' => 'blog',
                'icon' => null,
                'children' => [],
                'isActive' => false,
                'route'=> 'BlogList',
                'path' => '/dashboard/blog',
            ],
            [
                'title' => 'دسته بندی ها',
                'id' => 3,
                'type' => 'category',
                'icon' => null,
                'children' => [],
                'isActive' => false,
                'route'=> 'CategoryList',
                'path' => '/dashboard/category',
            ],

        ];


        $menu = [];
        foreach ($categories as $category) {
            $item = [
                'title' => $category->title,
                'id' => (int) $category->id,
                'type' => 'category',
                'icon' => null,
                'children' => [],
                'isActive' => false,
                'route'=> 'CategoryItem',
                'path' => null,
                'query' =>  null
            ];

            // کوئری برای دریافت زیر دسته‌بندی‌ها با parent_id برابر با category.id
            $subcategories = SubCategory::select('id', 'title', 'url' ,'parent_id')
                ->where('parent_id', $category->id)
                ->get();
            $query = [];
            foreach ($subcategories as $subcategory) {
                $item['children'][] = [
                    'title' => $subcategory->title,
                    'id' => (int) $subcategory->id,
                    'type' => 'sub_category',
                    'icon' => null,
                    'children' => [],
                    'isActive' => false,
                    'parent_id'=>(int) $subcategory->parent_id,
                    'route'=> 'CategorySubItem',
                    'path' => '/dashboard/category/' . $subcategory->parent_id,
                    'query' =>  // اضافه کردن query
                        [
                            'category' => (int) $subcategory->parent_id,
                            'subcategory' =>(int) $subcategory->id,
                        ],
                ];
            }

            $menu[] = $item;
        }
        $mainItems[1]['children'] = $menu;
        return response()->json($mainItems);
    }

}
