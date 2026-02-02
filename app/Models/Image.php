<?php

namespace App\Models;

use App\Http\Requests\ImageRequest;

class Image extends BaseModel
{
    protected $guarded = ['id'];

    /**
     * Загружает новое фото и сохраняет в БД.
     * Ожидает файл в запросе, например, request()->file('file').
     *
     */
    public static function add(ImageRequest $request)
    {
        $request = $request->validated();
        $image = Image::create([
            'name' => $request['name'],
            'path' => $request['file']->store('images', 'public'),
            'second_path' => !empty($request['second_file']) ? $request['second_file']->store('images', 'public') : null,
        ]);

        return $image;
    }
}
