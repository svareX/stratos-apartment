<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProxyController extends Controller
{
    /**
     * Serve a resized version of an image stored on the public disk.
     * Query parameters:
     * - path: path relative to the public disk (e.g. "photos/abc.jpg") or a URL containing "/storage/..."
     * - w: desired width (px)
     */
    public function proxy(Request $request)
    {
        $path = $request->query('path');
        $width = (int) $request->query('w');

        if (! is_string($path) || empty($path)) {
            abort(404);
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            $u = parse_url($path);
            $path = isset($u['path']) ? ltrim($u['path'], '/') : $path;
        }

        if (Str::startsWith($path, '/storage/')) {
            $path = substr($path, strlen('/storage/'));
        }

        if (Str::startsWith($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            abort(404);
        }

        $original = $disk->path($path);
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (! in_array($ext, $allowed)) {
            return response()->file($original);
        }

        if ($width <= 0) {
            return response()->file($original);
        }

        $cacheDir = storage_path('app/public/_cache');
        if (! is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cacheFile = $cacheDir . '/' . md5($path) . "-{$width}." . $ext;
        if (file_exists($cacheFile)) {
            return response()->file($cacheFile, ['Content-Type' => $this->mime($ext)]);
        }

        [$origW, $origH] = getimagesize($original);
        if (! $origW || ! $origH) {
            abort(404);
        }

        $newW = $width;
        $newH = (int) round($origH * $newW / $origW);

        switch ($ext) {
            case 'png':
                $src = imagecreatefrompng($original);
                break;
            case 'webp':
                $src = imagecreatefromwebp($original);
                break;
            case 'gif':
                $src = imagecreatefromgif($original);
                break;
            default:
                $src = imagecreatefromjpeg($original);
        }

        if (! $src) {
            abort(500);
        }

        $dst = imagecreatetruecolor($newW, $newH);

        if (in_array($ext, ['png', 'webp'])) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        switch ($ext) {
            case 'png':
                imagepng($dst, $cacheFile, 6);
                break;
            case 'webp':
                imagewebp($dst, $cacheFile, 80);
                break;
            case 'gif':
                imagegif($dst, $cacheFile);
                break;
            default:
                imagejpeg($dst, $cacheFile, 85);
        }

        imagedestroy($src);
        imagedestroy($dst);

        return response()->file($cacheFile, ['Content-Type' => $this->mime($ext)]);
    }

    protected function mime(string $ext): string
    {
        return match ($ext) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'image/jpeg',
        };
    }
}
