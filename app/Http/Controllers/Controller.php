<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginResponse;
use App\Http\Resources\SuccessResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Controller
{
    /**
     * Класс модели, который будет использоваться в этом контроллере.
     * Определите в субклассах, например: protected string $model = Vacancy::class;
     *
     * @var class-string<Model>
     */
    protected string $model;

    /**
     * Получить одну запись по ID (с route model binding).
     *
     * @param $item
     * @return JsonResource
     */
    public function get($item)
    {
        return $this->model::findOrFail($item) |> SuccessResponse::new(...);
    }

    /**.
     * Получить все записи с пагинацией.
     *
     * @param Request $request
     * @return PaginResponse
     */
    public function all(Request $request)
    {
        return new PaginResponse($this->model::basePagination($request, orderByDesc: 'updated_at'));
    }
}
