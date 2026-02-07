<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $res = [
            'success' => true,
            'message' => 'success',
            'data' => $this->resource ? $this->resource['data'] : '',
            'pagingInfo' => [
                'limit' => $this->resource['pagingInfo']['limit'],
                'offset' => $this->resource['pagingInfo']['offset'],
                'totalCount' => $this->resource['pagingInfo']['totalCount'],
            ]
        ];

        if ($this->resource['meta']) {
            $res['meta'] = $this->resource['meta'];
        }

        return $res;
    }
}
