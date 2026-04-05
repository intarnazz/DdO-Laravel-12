<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Resources\SuccessResponse;
use App\Models\Brand;
use App\Models\Contact;
use App\Models\Image;
use App\Models\Type;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

class ContextController
{
    public function get()
    {
        return [
                'brands' => Brand::all(),
                'types' => Type::all(),
                'contacts' => Contact::all(),
            ] |> SuccessResponse::new(...);
    }
}
