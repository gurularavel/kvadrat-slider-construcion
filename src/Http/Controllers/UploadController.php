<?php

namespace Msc\SliderConstructor\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file   = $request->file('file');
        $disk   = config('slider-constructor.upload_disk', 'public');
        $folder = config('slider-constructor.upload_folder', 'slider-uploads');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file'], 400);
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $ext     = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, $allowed)) {
            return response()->json(['error' => 'File type not allowed'], 400);
        }

        $filename = uniqid('slide_', true) . '.' . $ext;
        $path     = $file->storeAs($folder, $filename, $disk);

        if (!$path) {
            return response()->json(['error' => 'Upload failed'], 500);
        }

        $url = Storage::disk($disk)->url($path);

        // Resize with GD if available
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) && extension_loaded('gd')) {
            $fullPath = Storage::disk($disk)->path($path);
            $this->resizeIfNeeded($fullPath, $ext, 1920);
        }

        return response()->json([
            'success' => true,
            'url'     => $url,
            'file'    => $filename,
        ]);
    }

    private function resizeIfNeeded(string $path, string $ext, int $maxWidth): void
    {
        [$w, $h] = @getimagesize($path);
        if (!$w || $w <= $maxWidth) {
            return;
        }

        $ratio  = $maxWidth / $w;
        $newH   = (int) round($h * $ratio);

        $src = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png'         => @imagecreatefrompng($path),
            'gif'         => @imagecreatefromgif($path),
            default       => null,
        };

        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor($maxWidth, $newH);

        if ($ext === 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newH, $w, $h);

        match ($ext) {
            'jpg', 'jpeg' => imagejpeg($dst, $path, 88),
            'png'         => imagepng($dst, $path, 6),
            'gif'         => imagegif($dst, $path),
            default       => null,
        };

        imagedestroy($src);
        imagedestroy($dst);
    }
}
