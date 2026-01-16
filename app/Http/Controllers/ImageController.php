<?php

namespace App\Http\Controllers;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\SuccessResponse;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController
{
    /**
     * Возвращает фото по ID (в формате JSON или как файл).
     * Если нужно вернуть сам файл, используйте response()->file().
     *
     * @param int $id
     */
    public function get(Image $image)
    {
        return response()->file(storage_path('app/public/' . $image->path));
    }
    public function add(ImageRequest $request)
    {
        return new SuccessResponse(Image::add($request));
    }
}
