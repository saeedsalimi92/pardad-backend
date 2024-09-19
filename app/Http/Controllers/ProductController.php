<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::orderBy('created_at', 'desc')->paginate(15);
        foreach ($product as $item){
            $image = (new ImagesController)->showByParent('product' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $data = [];



        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'subtitle' => 'required|min:4',
            'description' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 400);
        }



        $product = new Product;
        $product->title = $request->title;
        $product->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $product->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $product->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $product->description = is_null($request->description) ? '' : $request->description;
        $product->en_description = is_null($request->en_description) ? '' : $request->en_description;


        $product->images =  json_encode($data);
        $product->status = is_null($request->status) ? 0 : $request->status;
        $product->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $product->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $product->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
        $product->canonical = is_null($request->canonical) ? '' : $request->canonical;
        $product->no_index = is_null($request->no_index) ? '' : $request->no_index;

        $product->save();
        $id = $product->latest()->first()->id;
        if($files = $request->file('image')){
            foreach($files as $file){
                $path = (new ImagesController)->store($file , 'images' ,'product' , $id);
                $data['images'][] = $path->original;
            }
        }
        $product->images = json_encode($data);

        $product->save();
        return response()->json([
            "message" => " سرویس با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $product = Product::find($id);
        if(!empty($product))
        {
            $image = (new ImagesController)->showByParent('product' , $id);
            $product->images = json_decode($image);
            return response()->json($product);
        }
        else
        {
            return response()->json([
                "message" => " سرویس یافت نشد"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $product->title = is_null($request->title) ? $product->title : $request->title;
            $product->en_title = is_null($request->en_title) ? $product->en_title : $request->en_title;
            $product->en_subtitle = is_null($request->en_subtitle) ? $product->en_subtitle : $request->en_subtitle;
            $product->subtitle = is_null($request->subtitle) ? $product->subtitle : $request->subtitle;
            $product->en_description = is_null($request->en_description) ? $product->en_description : $request->en_description;
            $product->description = is_null($request->description) ? $product->description : $request->description;
            $product->status = is_null($request->status) ? $product->status : $request->status;
            $product->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $product->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $product->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $product->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $product->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $product->save();
            return response()->json([
                "message" => " سرویس با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " سرویس یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $product->images = (new ImagesController)->showByParent('product' , $id);
            $data = [
                'images'=>json_decode($product->images)->images,
            ];
            $image = json_decode(($product->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','product' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $product->images = $data;
            $product->save();
            return response()->json([
                "message" => " سرویس با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " سرویس یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $product , $id)
    {

        if (Product::where('id' ,$product)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " سرویس یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if(Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $images =json_decode((new ImagesController)->showByParent('product' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $product->delete();

            return response()->json([
                "message" => " سرویس با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " سرویس یافت نشد."
            ], 404);
        }
    }

}
