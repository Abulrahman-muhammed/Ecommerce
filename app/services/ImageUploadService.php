<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $directory): string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($directory, $fileName, 'public');
    }

    public function delete(string $path): void
    {
        if (!$path) return;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
