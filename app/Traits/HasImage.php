<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function images(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function storeImage(UploadedFile $file, string $directory = 'images'): Image
    {
        $path = $file->store($directory, $this->getDisk());

        return $this->images()->create([
            'path' => $path,
            'disk' => $this->getDisk(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function updateImage(UploadedFile $file, int $imageId, string $directory = 'images'): ?Image
    {
        $image = $this->images()->find($imageId);

        if (!$image) {
            return null;
        }

        Storage::disk($image->disk)->delete($image->path);

        $path = $file->store($directory, $this->getDisk());

        $image->update([
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return $image;
    }

    public function deleteImage(int $imageId): bool
    {
        $image = $this->images()->find($imageId);

        if (!$image) {
            return false;
        }

        Storage::disk($image->disk)->delete($image->path);

        return $image->delete();
    }

    protected function getDisk(): string
    {
        return 'public';
    }

    public function featuredImage(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', Image::TYPE_FEATURED);
    }

    public function galleryImages(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->where('type', Image::TYPE_GALLERY);
    }

    public function storeFeaturedImage(UploadedFile $file, string $directory = 'images'): Image
    {
        $this->featuredImage()->delete();

        return $this->images()->create([
            'path' => $file->store($directory, $this->getDisk()),
            'disk' => $this->getDisk(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'type' => 'featured'
        ]);
    }

    public function storeGalleryImage(UploadedFile $file, string $directory = 'images'): Image
    {
        return $this->images()->create([
            'path' => $file->store($directory, $this->getDisk()),
            'disk' => $this->getDisk(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'type' => 'gallery'
        ]);
    }
}
