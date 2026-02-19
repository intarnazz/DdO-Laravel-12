<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Psy\Util\Json;

/**
 * @method static Builder where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @mixin Builder
 */
abstract class BaseModel extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    protected static array $sortable = [];

    /**
     * Список связей для "одиночного" eager loading.
     * Используется только при get-запросе конкретного элемента по id.
     *
     * Пример использования:
     * $model::with($model->with_one_only)->findOrFail($id);
     */
    protected array $with_one_only = [];

    /**
     * Флаг, указывающий, является ли модель древовидной (tree).
     * Если true, при получении одной записи через getOne
     * будет создан атрибут `tree` с дочерними элементами, у которых
     * self_id = id текущего объекта.
     *
     */
    protected static bool $isTree = false;


    /**
     * Получение одного объекта модели по его ID.
     * Подгружает связи, указанные в $with_one_only.
     * Если модель древовидная ($isTree = true), добавляет поле `tree`
     * с дочерними объектами (где self_id = id текущей записи).
     *
     * @param int $item ID объекта
     * @return Model Загруженный объект с подгруженными связями и, при необходимости, дочерними элементами в поле tree
     */
    public static function getOne(int $item): Model
    {
        $data = static::with((new static)->with_one_only)->findOrFail($item);

        if (static::$isTree) {
            $data->tree = static::where('self_id', $data->id)->get();
        }

        return $data;
    }

    #[\NoDiscard("The name must not be discarded")]
    protected static function _filter($query, $filters)
    {
        return $query;
    }


    protected static function _meta()
    {
        return null;
    }

    /**
     * Базовая пагинация по заданному условию.
     *
     * @param Request $request HTTP-запрос, из которого берутся limit и offset
     * @param array<string, mixed> $where Условия фильтрации (может быть пустым)
     * @param string $orderByDesc Название поля, по которому будет происходить сортировка по убыванию (если указано)
     *
     *
     * @return array{
     *     data: Collection,
     *     pagingInfo: array{
     *         limit: int,
     *         offset: int,
     *         totalCount: int
     *     }
     * }
     */
    protected static function basePagination(Request $request, array $where = [], string $orderByDesc = ''): array
    {
        $limit = (int)($request->header('limit') ?? 3);
        $offset = (int)($request->header('offset') ?? 0);
        $query = static::query();

        if ($request->header('filters')) {
            $filters = json_decode($request->header('filters'));
            $query = static::_filter($query, $filters);
        }

        /** @var User|null $user */
        $user = auth()->user();

        foreach ($where as $key => $value) {
            if ($key === 'user_id' && $user?->role === 'admin') {
                continue;
            }
            $query->where($key, $value);
        }

        if (static::$isTree) {
            $query->where('self_id', null);
        }


        $order = ($request->header('order') ?? '');
        $desc = ($request->header('desc') ?? '');
        if ($order != '') {
            if (
                static::$sortable && in_array($order, static::$sortable)
            ) {
                $desc == 'desc' ? $query->orderByDesc($order) : $query->orderBy($order);
            }
        } else {
            if ($orderByDesc !== '') {
                $query->orderByDesc($orderByDesc);
            } else {
                $query->orderBy('id');
            }
        }

        if ($request->header('ignore')) {
            $ignore = json_decode($request->header('ignore'));
            $query->whereIn('id', $ignore, not: true);

            $offset = 0;
        }

        $totalCount = $query->count();

        if ($request->header('names') ?? false) $query->whereIn('name', array_filter(array_map('trim', explode(',', $request->header('names')))));
        else $query
            ->offset($offset)
            ->limit($limit);

        $data = $query->get();
        $meta = static::_meta();

        return [
            'data' => $data,
            'meta' => $meta,
            'pagingInfo' => [
                'limit' => $data->count(),
                'offset' => $offset,
                'totalCount' => $totalCount,
            ]
        ];
    }
}
