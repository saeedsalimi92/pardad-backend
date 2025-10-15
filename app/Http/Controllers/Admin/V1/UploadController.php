<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin upload operations"
 * )
 */
class UploadController extends Controller
{

    public function index(Request $request)
    {

        $basePath = storage_path('app/public');
        $path = $request->get('path', '');
        $search = $request->get('search', '');
        $fullPath = realpath($basePath . '/' . $path);
        $baseUrl = url('storage'); 

        if (!$fullPath || !str_starts_with($fullPath, $basePath)) {
            return response()->json(['error' => 'Invalid path'], 400);
        }

        $files = $this->getAllFilesRecursively($fullPath, $basePath, $baseUrl);

        if ($search) {
            $files = $files->filter(fn($f) => str_contains(strtolower($f['name']), strtolower($search)));
        }

        $files = $files->sortByDesc('modified')->values();

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);
        $paged = new LengthAwarePaginator(
            $files->forPage($page, $perPage)->values(),
            $files->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return $this->apiResponse(data: $paged->items(), paginationData: PaginationResource::make($paged));
    }

    private function getAllFilesRecursively(string $path, string $basePath, string $baseUrl): Collection
    {
        $result = collect();

        foreach (File::allFiles($path) as $file) {
            $relativePath = str_replace($basePath . '/', '', $file->getPathname());
            $url = $baseUrl . '/' . str_replace('\\', '/', $relativePath);

            $result->push([
                'name' => $file->getFilename(),
                'url' => $url,
                'size' => $this->formatBytes($file->getSize()),
                'type' => $this->detectFileType($file->getExtension()),
                'created_at' => $file->getMTime(),
            ]);
        }

        return $result->sortByDesc('created_at');
    }

    private function detectFileType(string $extension): string
    {
        $extension = strtolower($extension);

        return match (true) {
            in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']) => 'Image',
            in_array($extension, ['mp4', 'avi', 'mkv', 'mov', 'webm']) => 'Video',
            in_array($extension, ['mp3', 'wav', 'ogg']) => 'Audio',
            in_array($extension, ['pdf']) => 'PDF',
            in_array($extension, ['zip', 'rar', '7z']) => 'Archive',
            in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']) => 'Document',
            default => 'other',
        };
    }

    private function formatBytes(int $bytes, int $decimals = 2): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);
        return sprintf("%.{$decimals}f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }

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
            $file->storeAs('image', $fileName, 'public');

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
            $file->storeAs('media', $fileName, 'public');

            return response()->json([
                'location' => asset('storage/media/' . $fileName)
            ]);
        }

        return response()->json(['error' => 'فایل آپلود نشد.'], 400);
    }
}
