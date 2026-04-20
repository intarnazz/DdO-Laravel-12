<?php

namespace App\Models;

use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\Storage;

class Image extends BaseModel
{
    /**
     * Загружает новое фото и сохраняет в БД.
     * Ожидает файл в запросе, например, request()->file('file').
     *
     */
    public static function add(array $data)
    {
        return Image::create([
            'name' => $data['name'],
            'path' => $data['file']->store('images', 'public'),
        ]);
    }

    public static function remove(Image $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
    }
}
