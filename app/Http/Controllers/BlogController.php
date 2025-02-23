<?php

namespace App\Http\Controllers;

use App\Models\Blog;

use App\Models\Category;
use App\Models\subCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\Util\Json;
use Validator;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::orderBy('created_at', 'desc')->paginate(15);
        foreach ($blog as $item){
            $image = (new ImagesController)->showByParent('blog' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($blog);
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
            return response()->json(['error' => $error], 400);
        }

        $subCategory = subCategory::find($request->parent_id);
        if (!empty($subCategory)) {
            $blog = new Blog;
            $blog->title = $request->title;
            $blog->en_title = is_null($request->en_title) ? '' : $request->en_title;
            $blog->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
            $blog->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
            $blog->description = is_null($request->description) ? '' : $request->description;
            $blog->en_description = is_null($request->en_description) ? '' : $request->en_description;
            $blog->instagram = is_null($request->instagram) ? '' : $request->instagram;
            $blog->telegram = is_null($request->telegram) ? '' : $request->telegram;
            $blog->whatsapp = is_null($request->whatsapp) ? '' : $request->whatsapp;
            $blog->author = is_null($request->author) ? '' : $request->author;
            $blog->parent_id = is_null($request->parent_id) ? '' : $request->parent_id;
            $blog->images =  json_encode($data);
            $blog->logos =  json_encode($data);
            $blog->status = is_null($request->status) ? 0 : $request->status;
            $blog->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $blog->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $blog->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $blog->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $blog->no_index = is_null($request->no_index) ? '' : $request->no_index;

            $blog->save();
            $id = $blog->latest()->first()->id;
            if($files = $request->file('image')){
                foreach($files as $file){
                    $path = (new ImagesController)->store($file , 'images' ,'blog' , $id);
                    $data['images'][] = $path->original;
                }
            }
            $blog->images = json_encode($data);

            $blog->save();
            return response()->json([
                "message" => " بلاگ با موفقیت اضافه شد."
            ], 201);
        }else{
            return response()->json(['error' => 'زیر شاخه الزامی است.'], 400);
        }




    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if(!empty($blog))
        {
            $data =array();
            $image = (new ImagesController)->showByParent('blog' , $id);
            $blog->images = json_decode($image);



            $blog->images = json_decode($image);
            $blog->logos = is_null($blog->logos) ? Null: json_decode($blog->logos);

            foreach ($blog->logos as $item){
                $logos = (new LogoController)->show($item);
                array_push($data , $logos->original);
            }


            $blog->logos_image = $data;

            return response()->json($blog);
        }
        else
        {
            return response()->json([
                "message" => " بلاگ یافت نشد"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $blog->title = is_null($request->title) ? $blog->title : $request->title;
            $blog->en_title = is_null($request->en_title) ? $blog->en_title : $request->en_title;
            $blog->en_subtitle = is_null($request->en_subtitle) ? $blog->en_subtitle : $request->en_subtitle;
            $blog->subtitle = is_null($request->subtitle) ? $blog->subtitle : $request->subtitle;
            $blog->en_description = is_null($request->en_description) ? $blog->en_description : $request->en_description;
            $blog->description = is_null($request->description) ? $blog->description : $request->description;
            $blog->status = is_null($request->status) ? $blog->status : $request->status;
            $blog->instagram = is_null($request->instagram) ? $blog->instagram : $request->instagram;
            $blog->telegram = is_null($request->telegram) ? $blog->telegram : $request->telegram;
            $blog->whatsapp = is_null($request->whatsapp) ? $blog->whatsapp : $request->whatsapp;
            $blog->parent_id = is_null($request->parent_id) ? '' : $request->parent_id;
            $blog->author = is_null($request->author) ? $blog->author : $request->author;
            $blog->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $blog->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $blog->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $blog->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $blog->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $blog->save();
            return response()->json([
                "message" => " بلاگ با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }
    public function updateLogos(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);

            Log::info($request->logos);

            $blog->Logos = is_null($request->logos) ? Null : $request->logos;


            $blog->save();
            return response()->json([
                "message" => " لوگوی خبر با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $blog->images = (new ImagesController)->showByParent('blog' , $id);
            $data = [
                'images'=>json_decode($blog->images)->images,
            ];
            $image = json_decode(($blog->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','blog' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $blog->images = $data;
            $blog->save();
            return response()->json([
                "message" => " بلاگ با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $blog , $id)
    {

        if (Blog::where('id' ,$blog)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if(Blog::where('id', $id)->exists()) {
            $blog = Blog::find($id);
            $images =json_decode((new ImagesController)->showByParent('blog' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $blog->delete();

            return response()->json([
                "message" => " بلاگ با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " بلاگ یافت نشد."
            ], 404);
        }
    }
}
