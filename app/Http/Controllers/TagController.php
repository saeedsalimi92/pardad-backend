<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

use App\Models\Tag;
use Illuminate\Http\Request;
use Validator;

class TagController extends Controller
{
    public function index()
    {
        $tag = Tag::orderBy('created_at', 'desc')->paginate(15);
        return response()->json($tag);
    }

    public function store(Request $request)
    {




        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4',
            'subtitle' => 'required|min:4',
            'description' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 400);
        }



        $tag = new Tag;
        $tag->title = $request->title;
        $tag->en_title = is_null($request->en_title) ? '' : $request->en_title;
        $tag->en_subtitle = is_null($request->en_subtitle) ? '' : $request->en_subtitle;
        $tag->subtitle = is_null($request->subtitle) ? '' : $request->subtitle;
        $tag->description = is_null($request->description) ? '' : $request->description;
        $tag->en_description = is_null($request->en_description) ? '' : $request->en_description;


        $tag->status = is_null($request->status) ? 0 : $request->status;

        $tag->save();
        return response()->json([
            "message" => " تگ با موفقیت اضافه شد."
        ], 201);

    }

    public function show($id)
    {
        $tag = Tag::find($id);
        if(!empty($tag))
        {
            return response()->json($tag);
        }
        else
        {
            return response()->json([
                "message" => " تگ یافت نشد"
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Tag::where('id', $id)->exists()) {
            $tag = Tag::find($id);
            $tag->title = is_null($request->title) ? $tag->title : $request->title;
            $tag->en_title = is_null($request->en_title) ? $tag->en_title : $request->en_title;
            $tag->en_subtitle = is_null($request->en_subtitle) ? $tag->en_subtitle : $request->en_subtitle;
            $tag->subtitle = is_null($request->subtitle) ? $tag->subtitle : $request->subtitle;
            $tag->en_description = is_null($request->en_description) ? $tag->en_description : $request->en_description;
            $tag->description = is_null($request->description) ? $tag->description : $request->description;
            $tag->status = is_null($request->status) ? $tag->status : $request->status;
            $tag->save();
            return response()->json([
                "message" => " تگ با موفقیت بروزرسانی شد"
            ], 200);
        }else{
            return response()->json([
                "message" => " تگ یافت نشد."
            ], 404);
        }
    }


    public function destroy($id)
    {
        if(Tag::where('id', $id)->exists()) {
            $tag = Tag::find($id);

            $tag->delete();

            return response()->json([
                "message" => " تگ با موفقیت اضافه شد."
            ], 202);
        } else {
            return response()->json([
                "message" => " تگ یافت نشد."
            ], 404);
        }
    }
}
