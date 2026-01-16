<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NameToKeyResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $result = [];
        foreach ($this->resource as $value) {
            $result[$value->name] = $value;
        };
        return $result;
    }
}
