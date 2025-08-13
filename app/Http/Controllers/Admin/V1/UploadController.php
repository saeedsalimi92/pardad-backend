<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin upload operations"
 * )
 */
class UploadController extends Controller
{

    public function uploadFile(Request $request)
    {
        $request->validate([
            'newFile' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240', // حداکثر 10MB
        ]);

        if ($request->hasFile('newFile')) {
            $file = $request->file('newFile');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($originalName) . '.' . $extension;



            // ذخیره در پوشه مناسب بر اساس نوع فایل
            $folder = 'documents';
            $file->storeAs($folder, $fileName, 'public');

            return response()->json([
                'location' => asset("storage/file/" . $fileName),
                'filename' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType()
            ]);
        }

        return response()->json(['error' => 'فایل آپلود نشد.'], 400);
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
    public function uploadImages(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10', // حداکثر 10 فایل
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // اعتبارسنجی هر فایل
        ]);

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('image', $fileName, 'public');
                $uploadedFiles[] = asset('storage/image/' . $fileName);
            }

            return response()->json([
                'locations' => $uploadedFiles
            ]);
        }

        return response()->json(['error' => 'هیچ فایلی آپلود نشد.'], 400);
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
