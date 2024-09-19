<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use Illuminate\Http\Request;
use Validator;
class TeamsController extends Controller
{
    public function index()
    {
        $team = Teams::orderBy('created_at', 'desc')->paginate(15);
        foreach ($team as $item){
            $image = (new ImagesController)->showByParent('team' , $item->id);
            $item->images = json_decode($image);
        }

        return response()->json($team);
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



        $team = new Teams;
        $team->title = $request->title;
        $team->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $team->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $team->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $team->description = is_null($request->description) ? '' : $request->description;
        $team->en_description = is_null($request->en_description) ? '' : $request->en_description;
        $team->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
        $team->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
        $team->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
        $team->email = is_null($request->email) ? '' : $request->email;
        $team->telegram = is_null($request->telegram) ? '' : $request->telegram;
        $team->instagram = is_null($request->instagram) ? '' : $request->instagram;
        $team->whatsapp = is_null($request->whatsapp) ? '' : $request->whatsapp;
        $team->canonical = is_null($request->canonical) ? '' : $request->canonical;
        $team->no_index = is_null($request->no_index) ? '' : $request->no_index;

        $team->images =  json_encode($data);
        $team->status = is_null($request->status) ? 0 : $request->status;

        $team->save();
        $id = $team->latest()->first()->id;
        if($files = $request->file('image')){
            foreach($files as $file){
                $path = (new ImagesController)->store($file , 'images' ,'team' , $id);
                $data['images'][] = $path->original;
            }
        }
        $team->images = json_encode($data);

        $team->save();
        return response()->json([
            "message" => " تیم با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $team = Teams::find($id);
        if(!empty($team))
        {
            $image = (new ImagesController)->showByParent('team' , $id);
            $team->images = json_decode($image);
            return response()->json($team);
        }
        else
        {
            return response()->json([
                "message" => " تیم یافت نشد"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Teams::where('id', $id)->exists()) {
            $team = Teams::find($id);
            $team->title = is_null($request->title) ? $team->title : $request->title;
            $team->en_title = is_null($request->en_title) ? $team->en_title : $request->en_title;
            $team->en_subtitle = is_null($request->en_subtitle) ? $team->en_subtitle : $request->en_subtitle;
            $team->subtitle = is_null($request->subtitle) ? $team->subtitle : $request->subtitle;
            $team->en_description = is_null($request->en_description) ? $team->en_description : $request->en_description;
            $team->description = is_null($request->description) ? $team->description : $request->description;
            $team->status = is_null($request->status) ? $team->status : $request->status;
            $team->meta_description = is_null($request->meta_description) ? '' : $request->meta_description;
            $team->meta_title = is_null($request->meta_title) ? '' : $request->meta_title;
            $team->meta_keywords = is_null($request->meta_keywords) ? '' : $request->meta_keywords;
            $team->telegram = is_null($request->telegram) ? '' : $request->telegram;
            $team->instagram = is_null($request->instagram) ? '' : $request->instagram;
            $team->whatsapp = is_null($request->whatsapp) ? '' : $request->whatsapp;
            $team->canonical = is_null($request->canonical) ? '' : $request->canonical;
            $team->no_index = is_null($request->no_index) ? '' : $request->no_index;
            $team->save();
            return response()->json([
                "message" => " تیم با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " تیم یافت نشد."
            ], 404);
        }
    }

    public function updateImages(Request $request, $id)
    {
        if (Teams::where('id', $id)->exists()) {
            $team = Teams::find($id);
            $team->images = (new ImagesController)->showByParent('team' , $id);
            $data = [
                'logo'=>json_decode($team->images)->logo,
                'back_image'=>json_decode($team->images)->back_image,
                'images'=>json_decode($team->images)->images,
                'video'=>json_decode($team->images)->video,
            ];
            $image = json_decode(($team->images),true);

            if($request->has_images == "true"){

                if($files = $request->file('images')){
                    foreach($files as $file){


                        $path = (new ImagesController)->store($file , 'images','team' , $id);
                        foreach($image as $key => $value) {
                            if($key == 'images') {
                                array_push($image['images'],$path->original);
                            }
                        }
                    }

                }
                $data['images'] = $image['images'];
            }



            $team->images = $data;
            $team->save();
            return response()->json([
                "message" => " تیم با موفقیت بروز رسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " تیم یافت نشد."
            ], 404);
        }
    }

    public function deleteImage(Request $request , $team , $id)
    {

        if (Teams::where('id' ,$team)->exists()) {
            $path = (new ImagesController)->show($id);


            $img = json_decode($path->original);


            (new ImagesController)->destroy($img->id);

            return response()->json([
                "message" => "تصویر با موفقیت حذف شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " تیم یافت نشد."
            ], 404);
        }
    }

    public function destroy($id)
    {
        if(Teams::where('id', $id)->exists()) {
            $team = Teams::find($id);
            $images =json_decode((new ImagesController)->showByParent('team' , $id) , true) ;
            $ids= array();
            if(key($images['images'] ) === 'id'){
                foreach($images['images'] as $file){
                    array_push($ids ,$file['id']);
                }
            }


            (new ImagesController)->destroyMany($ids);
            $team->delete();

            return response()->json([
                "message" => " تیم با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " تیم یافت نشد."
            ], 404);
        }
    }
}
