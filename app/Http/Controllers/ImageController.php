<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Resources\SuccessResponse;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

class ImageController
{
    public function get(Image $image, Request $request)
    {
        $maxW = $request->integer('w') ?: null;
        $maxH = $request->integer('h') ?: null;

        $originalPath = storage_path('app/public/' . $image->path);

        // Оригинал — отдаём сразу, без обработки
        if (!$maxW && !$maxH) {
            return response()->file($originalPath);
        }

        $ext = pathinfo($image->path, PATHINFO_EXTENSION);
        $cachedPath = storage_path("app/public/cache/resized/{$image->id}_{$maxW}x{$maxH}.{$ext}");

        if (!file_exists($cachedPath)) {
            if (!is_dir(dirname($cachedPath))) {
                mkdir(dirname($cachedPath), 0755, true);
            }

            $img = ImageFacade::decode(file_get_contents($originalPath));
            $img->scale(width: $maxW, height: $maxH);
            $img->save($cachedPath);
        }

        return response()->file($cachedPath);
    }

    public function add(ImageRequest $request)
    {
        return new SuccessResponse(Image::add($request));
    }
}
