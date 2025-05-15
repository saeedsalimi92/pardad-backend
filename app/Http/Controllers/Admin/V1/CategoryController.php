<?php

namespace App\Http\Controllers\Admin\V1;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Validator;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin category operations"
 * )
 */
class CategoryController extends Controller
{

    public function index()
    {
        $perPage = request()->input('per_page', 15);

        $category = Category::orderBy('created_at', 'desc')->paginate($perPage);


        return response()->json($category);
    }
    public function client_Index()
    {
        $perPage = request()->input('per_page', 15);

        $category = Category::where('status', 'active')
            ->orderBy('created_at', 'desc')->paginate($perPage);


        return response()->json($category);
    }
    public function client_index_parent()
    {

        $categories = Category::select('id', 'title', 'url')->get();

        $mainItems = [



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
                ];
            }

            $menu[] = $item;
        }
        $mainItems = $menu;
        return response()->json($mainItems);

    }

    public function list()
    {
        $category = Category::all();
        $data = [];

        foreach ($category as $item) {

            $data[] = (object)[
                'title' => $item->title,
                'url' => $item->url,
                'id' => $item->id,
                'type'=>'category',
                'value' => $item->id,
            ];

        }
        return response()->json($data);
    }

    public function client_list()
    {
        $category = Category::where('status', 'active');
        $data = [];

        foreach ($category as $item) {

            $data[] = (object)[
                'title' => $item->title,
                'url' => $item->url,
                'id' => $item->id,
                'type'=>'category',
                'value' => $item->id,
            ];

        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // پیام‌های خطا
        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'description' => 'required|min:4',
        ], $messages);

        // اگر اعتبارسنجی با خطا مواجه شد
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }
        $data = [];
        // تولید URL
        $data['url'] = $this->generateUrl($request->url, $request->title);


        // ایجاد دسته‌بندی جدید
        $category = new Category;
        $category->title = $request->title;
        $category->url = $data['url'];
        $category->description = $request->description;

        // تنظیم مقادیر پیش‌فرض برای فیلدهای اختیاری
        $category->en_title = $request->en_title ?? '';
        $category->en_subtitle = $request->en_subtitle ?? '';
        $category->subtitle = $request->subtitle ?? '';
        $category->en_description = $request->en_description ?? '';
        $category->status = $request->status ?? 'inactive';
        $category->meta_description = $request->meta_description ?? '';
        $category->meta_title = $request->meta_title ?? '';
        $category->meta_keywords = $request->meta_keywords ?? '';
        $category->canonical = $request->canonical ?? '';
        $category->no_index = $request->no_index ?? 'inactive';

        $category->save();

        return response()->json([
            "message" => " دسته بندی با موفقیت اضافه شد."
        ], 201);
    }


    public function show($id)
    {
        $category = Category::find($id);
        if (!empty($category)) {
            return response()->json($category);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد"
            ], 404);
        }
    }
    public function client_show($id)
    {
        $category = Category::find($id);

        if (empty($category) || $category->status === 'inactive') {
            return response()->json([
                "message" => "دسته‌بندی یافت نشد یا غیرفعال است"
            ], 404);
        }

        return response()->json($category);
    }


    public function update(Request $request, $id)
    {

        // پیام‌های خطا
        $messages = [
            'title.required' => 'عنوان الزامی است.',
            'id.required' => 'شناسه دسته بندی الزامی است.',
            'title.min' => 'عنوان باید حداقل ۴ کاراکتر باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.min' => 'توضیحات باید حداقل ۴ کاراکتر باشد.',
        ];

        // اعتبارسنجی فیلدهای الزامی
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'description' => 'required|min:4',
        ], $messages);

        // اگر اعتبارسنجی با خطا مواجه شد
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 422);
        }


        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);

            $category->title = $request->has('title') ? (!empty($request->title) ? $request->title : '') : ($category->title ?? '');
            $category->en_title = $request->has('en_title') ? (!empty($request->en_title) ? $request->en_title : '') : ($category->en_title ?? '');
            $category->en_subtitle = $request->has('en_subtitle') ? (!empty($request->en_subtitle) ? $request->en_subtitle : '') : ($category->en_subtitle ?? '');
            $category->subtitle = $request->has('subtitle') ? (!empty($request->subtitle) ? $request->subtitle : '') : $category->subtitle;
            $category->en_description = $request->has('en_description') ? (!empty($request->en_description) ? $request->en_description : '') : $category->en_description;
            $category->description = $request->has('description') ? (!empty($request->description) ? $request->description : '') : $category->description;
            $category->meta_description = $request->has('meta_description') ? (!empty($request->meta_description) ? $request->meta_description : '') : $category->meta_description;
            $category->meta_title = $request->has('meta_title') ? (!empty($request->meta_title) ? $request->meta_title : '') : $category->meta_title;
            $category->meta_keywords = $request->has('meta_keywords') ? (!empty($request->meta_keywords) ? $request->meta_keywords : '') : $category->meta_keywords;
            $category->canonical = $request->has('canonical') ? (!empty($request->canonical) ? $request->canonical : '') : $category->canonical;
            $category->no_index = $request->has('no_index') ? (!empty($request->no_index) ? $request->no_index : 'inactive') : $category->no_index;
            $category->status = $request->has('status') ? (!empty($request->status) ? $request->status : 'inactive') : $category->status;

            $data = $request->all();
            $data['url'] = $this->generateUrl($request->url, $request->title);
            $category->url = $data['url'];



            $category->save();

            return response()->json([
                "message" => "دسته بندی با موفقیت بروزرسانی شد."
            ], 200);
        } else {
            return response()->json([
                "message" => "دسته بندی یافت نشد."
            ], 404);
        }
    }

    private function generateUrl($url, $title)
    {
        return $url ? str_replace(' ', '-', $url) : str_replace(' ', '-', $title);
    }

    public function destroy($id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);

            $category->delete();

            return response()->json([
                "message" => " دسته بندی با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " دسته بندی یافت نشد."
            ], 404);
        }
    }
}
