<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * @method static Builder where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @mixin Builder
 */
class BaseModel extends Model
{
    protected $guarded = ['id'];

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

        /** @var User|null $user */
        $user = auth()->user();

        foreach ($where as $key => $value) {
            if ($key === 'user_id' && $user?->role === 'admin') {
                continue;
            }
            $query->where($key, $value);
        }

        $totalCount = $query->count();

        if ($orderByDesc !== '') {
            $query->orderByDesc($orderByDesc);
        } else {
            $query->orderBy('id');
        }

        $query
            ->offset($offset)
            ->limit($limit);

        $data = $query->get();

        return [
            'data' => $data,
            'pagingInfo' => [
                'limit' => $data->count(),
                'offset' => $offset,
                'totalCount' => $totalCount,
            ]
        ];
    }
}
