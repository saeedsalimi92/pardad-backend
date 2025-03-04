<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('image', $fileName,'public');

            return response()->json([
                'location' => asset('storage/image/' . $fileName)
            ]);
        }

        return response()->json(['error' => 'فایل آپلود نشد.'], 400);
    }
    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:mp4,mp3,ogg,webm|max:10240', // حداکثر 10 مگابایت
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('media', $fileName,'public');

            return response()->json([
                'location' => asset('storage/media/' . $fileName)
            ]);
        }

        return response()->json(['error' => 'فایل آپلود نشد.'], 400);
    }
}
