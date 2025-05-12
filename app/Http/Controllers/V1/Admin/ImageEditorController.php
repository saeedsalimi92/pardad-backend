<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImageEditor;
use Illuminate\Http\Request;

class ImageEditorController extends Controller
{
    public function index(Request $request)
    {
        $images = ImageEditor::orderBy('created_at', 'desc')->simplePaginate(25);
        return response()->json($images);
    }
    public function store($image_id, $user_id)
    {
        $image = new ImageEditor();
        $image->user_id = $user_id;
        $image->image_id = $image_id;
        $image->save();
        return response()->json([
            "data" => null,
            "message" => "با موفقیت انجام شد"
        ], 200);
    }
    public function show($id)
    {
        $image = ImageEditor::find($id);

        if (!empty($image)) {
            return response()->json($image);
        } else {
            return response()->json([
                "data" => null,
                "message" => "عکس یافت نشد"
            ], 404);
        }
    }
    public function showByImage($id)
    {
        $image = ImageEditor::where('image_id' , $id)->get();

        if (!empty($image)) {
            return response()->json($image);
        } else {
            return response()->json([
                "data" => null,
                "message" => "عکس یافت نشد"
            ], 404);
        }
    }
}
