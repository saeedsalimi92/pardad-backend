<?php

namespace App\Http\Controllers;


use App\Models\Services;
use Illuminate\Http\Request;

use Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $service = Services::orderBy('created_at', 'desc')->paginate(15);
        foreach ($service as $item){
            $image = (new ImagesController)->showByParent('service' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($service);
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



        $service = new Services;
        $service->title = $request->title;
        $service->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $service->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $service->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $service->description = is_null($request->description) ? '' : $request->description;
        $service->en_description = is_null($request->en_description) ? '' : $request->en_description;


        $service->images =  json_encode($data);
        $service->status = is_null($request->status) ? 0 : $request->status;

        $service->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $service->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $service->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
        $service->canonical = is_null($request->canonical) ? '' : $request->canonical;
        $service->no_index = is_null($request->no_index) ? '' : $request->no_index;

        $service->save();
        $id = $service->latest()->first()->id;
        if($files = $request->file('image')){
            foreach($files as $file){
                $path = (new ImagesController)->store($file , 'images' ,'service' , $id);
                $data['images'][] = $path->original;
            }
        }
        $service->images = json_encode($data);

        $service->save();
        return response()->json([
            "message" => " سرویس با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $service = Services::find($id);
        if(!empty($service))
        {
            $image = (new ImagesController)->showByParent('service' , $id);
            $service->images = json_decode($image);
            return response()->json($service);
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
        if (Services::where('id', $id)->exists()) {
            $service = Services::find($id);
            $service->title = is_null($request->title) ? $service->title : $request->title;
            $service->en_title = is_null($request->en_title) ? $service->en_title : $request->en_title;
            $service->en_subtitle = is_null($request->en_subtitle) ? $service->en_subtitle : $request->en_subtitle;
            $service->subtitle = is_null($request->subtitle) ? $service->subtitle : $request->subtitle;
            $service->en_description = is_null($request->en_description) ? $service->en_description : $request->en_description;
            $service->description = is_null($request->description) ? $service->description : $request->description;
            $service->status = is_null($request->status) ? $service->status : $request->status;
            $service->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $service->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $service->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $service->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $service->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $service->save();
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
        if (Services::where('id', $id)->exists()) {
            $service = Services::find($id);
            $service->images = (new ImagesController)->showByParent('service' , $id);
            $data = [
                'images'=>json_decode($service->images)->images,
            ];
            $image = json_decode(($service->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','service' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $service->images = $data;
            $service->save();
            return response()->json([
                "message" => " سرویس با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " سرویس یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $service , $id)
    {

        if (Services::where('id' ,$service)->exists()) {
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
        if(Services::where('id', $id)->exists()) {
            $service = Services::find($id);
            $images =json_decode((new ImagesController)->showByParent('service' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $service->delete();

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
