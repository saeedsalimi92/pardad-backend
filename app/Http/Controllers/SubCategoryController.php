<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategory = SubCategory::orderBy('created_at', 'desc')->paginate(15);
        foreach ($subcategory as $item) {
            $image = (new ImagesController)->showByParent('subcategory', $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($subcategory);
    }

    public function list()
    {

        $category = SubCategory::all();
        $data = [];

        foreach ($category as $item){

            $data[] = (object) [
                'label' => $item->title,
                'value' => $item->id
            ];

        }
        return response()->json($data);
    }


    public function listByParent(Request $request)
    {

        $category = SubCategory::where('parent_id', $request->parent_id)->get();


        $data = [];

        foreach ($category as $item){

            $data[] = (object) [
                'label' => $item->title,
                'value' => $item->id
            ];

        }

        return response()->json($data);
    }
    public function getByParent(Request $request)
    {

        $category = SubCategory::where('parent_id', $request->parent_id)->get();



        return response()->json($category);
    }
    public function store(Request $request)
    {
        $data = [];
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'description' => 'required|min:4',
            'parent_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 400);
        }

        $category = Category::find($request->parent_id);
        if (!empty($category)) {
            $subcategory = new SubCategory;
            $subcategory->title = $request->title;
            $subcategory->en_title = is_null($request->en_title) ? '' : $request->en_title;
            $subcategory->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
            $subcategory->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
            $subcategory->description = is_null($request->description) ? '' : $request->description;
            $subcategory->en_description = is_null($request->en_description) ? '' : $request->en_description;
            $subcategory->parent_id = is_null($request->parent_id) ? '' : $request->parent_id;
            $subcategory->images = json_encode($data);
            $subcategory->logos = json_encode($data);
            $subcategory->status = is_null($request->status) ? 0 : $request->status;
            $subcategory->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $subcategory->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $subcategory->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $subcategory->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $subcategory->no_index = is_null($request->no_index) ? '' : $request->no_index;

            $subcategory->save();
            $id = $subcategory->latest()->first()->id;
            if ($files = $request->file('image')) {
                foreach ($files as $file) {
                    $path = (new ImagesController)->store($file, 'images', 'subcategory', $id);
                    $data['images'][] = $path->original;
                }
            }
            $subcategory->images = json_encode($data);

            $subcategory->save();
            return response()->json([
                "message" => " دسته بندی با موفقیت اضافه شد."
            ], 201);
        }else{
            return response()->json(['message' => 'دسته بندی الزامی است.'], 400);
        }
    }

    public function show($id)
    {
        $subcategory = SubCategory::find($id);
        if (!empty($subcategory)) {
            $data = array();
            $image = (new ImagesController)->showByParent('subcategory', $id);
            $subcategory->images = json_decode($image);

            $subcategory->images = json_decode($image);
            $subcategory->logos = is_null($subcategory->logos) ? Null : json_decode($subcategory->logos);

            foreach ($subcategory->logos as $item) {
                $logos = (new LogoController)->show($item);
                array_push($data, $logos->original);
            }


            $subcategory->logos_image = $data;

            return response()->json($subcategory);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);
            $subcategory->title = is_null($request->title) ? $subcategory->title : $request->title;
            $subcategory->en_title = is_null($request->en_title) ? $subcategory->en_title : $request->en_title;
            $subcategory->en_subtitle = is_null($request->en_subtitle) ? $subcategory->en_subtitle : $request->en_subtitle;
            $subcategory->subtitle = is_null($request->subtitle) ? $subcategory->subtitle : $request->subtitle;
            $subcategory->en_description = is_null($request->en_description) ? $subcategory->en_description : $request->en_description;
            $subcategory->description = is_null($request->description) ? $subcategory->description : $request->description;
            $subcategory->status = is_null($request->status) ? $subcategory->status : $request->status;

            $subcategory->parent_id = is_null($request->parent_id) ? '' : $request->parent_id;
            $subcategory->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $subcategory->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $subcategory->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $subcategory->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $subcategory->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $subcategory->save();
            return response()->json([
                "message" => " زیر شاخه با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function updateLogos(Request $request, $id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);

            Log::info($request->logos);

            $subcategory->Logos = is_null($request->logos) ? Null : $request->logos;


            $subcategory->save();
            return response()->json([
                "message" => " زیر شاخه با موفقیت بروزرسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);
            $subcategory->images = (new ImagesController)->showByParent('subcategory', $id);
            $data = [
                'images' => json_decode($subcategory->images)->images,
            ];
            $image = json_decode(($subcategory->images), true);

            if ($request->has_images == "true") {

                if ($files = $request->file('images')) {
                    foreach ($files as $file) {


                        $path = (new ImagesController)->store($file, 'images', 'subcategory', $id);
                        foreach ($image as $key => $value) {
                            if ($key == 'images') {
                                array_push($image['images'], $path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }


            $subcategory->images = $data;
            $subcategory->save();
            return response()->json([
                "message" => " زیر شاخه با موفقیت بروز رسانی شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request, $subcategory, $id)
    {

        if (SubCategory::where('id', $subcategory)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if (SubCategory::where('id', $id)->exists()) {
            $subcategory = SubCategory::find($id);
            $images = json_decode((new ImagesController)->showByParent('subcategory', $id), true);
            $ids = array();
            if (key($images['images']) === 'id') {
                foreach ($images['images'] as $file) {
                    array_push($ids, $file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $subcategory->delete();

            return response()->json([
                "message" => " زیر شاخه با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " زیر شاخه یافت نشد."
            ], 404);
        }
    }
}
