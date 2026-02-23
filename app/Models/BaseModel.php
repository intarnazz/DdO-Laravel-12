<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Psy\Util\Json;

/**
 * Базовая абстрактная модель, расширяющая Eloquent Model.
 * Предоставляет общие методы для работы с данными, включая пагинацию, фильтрацию и поиск.
 *
 * @method static Builder where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @mixin Builder
 */
abstract class BaseModel extends Model
{
    /**
     * Массив полей, которые не могут быть массово присвоены (защищены от массового заполнения).
     * По умолчанию защищено поле 'id'.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Массив полей, которые скрыты при сериализации модели (например, в JSON).
     * По умолчанию скрыты 'created_at', 'updated_at' и 'pivot'.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * Массив полей, по которым разрешена сортировка.
     * Используется для проверки допустимости сортировки в запросах.
     *
     * @var array
     */
    protected static array $sortable = [];

    /**
     * Список связей для "одиночного" eager loading.
     * Используется только при get-запросе конкретного элемента по id.
     *
     * Пример использования:
     * $model::with($model->with_one_only)->findOrFail($id);
     *
     * @var array
     */
    protected array $with_one_only = [];

    /**
     * Флаг, указывающий, является ли модель древовидной (tree).
     * Если true, при получении одной записи через getOne
     * будет создан атрибут `tree` с дочерними элементами, у которых
     * self_id = id текущего объекта.
     *
     * @var bool
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

    /**
     * Внутренний метод для применения фильтров к запросу.
     * Может быть переопределен в дочерних классах для реализации специфической фильтрации.
     *
     * @param Builder $query Конструктор запроса Eloquent.
     * @param mixed $filters Фильтры, полученные из запроса (обычно JSON-декодированные).
     * @return Builder Модифицированный конструктор запроса.
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _filter($query, $filters)
    {
        return $query;
    }

    /**
     * Внутренний метод для получения метаданных.
     * Может быть переопределен в дочерних классах для возврата дополнительных мета-данных.
     *
     * @return mixed|null Метаданные или null, если не определены.
     */
    protected static function _meta()
    {
        return null;
    }

    /**
     * Применяет фильтрацию поиска и сортировку по релевантности с использованием Laravel Scout.
     *
     * Этот метод принимает конструктор запроса Eloquent и строку поиска,
     * использует Scout для поиска соответствующих ID, отсортированных по релевантности,
     * и модифицирует запрос для фильтрации и сортировки соответственно.
     *
     * @param Builder $query Конструктор запроса Eloquent для модификации.
     * @param string $search Строка поискового запроса.
     * @return Builder Модифицированный конструктор запроса.
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _search(Builder $query, string $search): Builder
    {
        return $query;
    }

    /**
     * Базовая пагинация по заданному условию.
     *
     * Метод обрабатывает пагинацию, фильтрацию, поиск и сортировку на основе параметров HTTP-запроса.
     * Поддерживает фильтры, поиск, игнорирование ID, сортировку и метаданные.
     *
     * @param Request $request HTTP-запрос, из которого берутся limit, offset, search, filters и другие параметры.
     * @param array<string, mixed> $where Условия фильтрации (может быть пустым).
     * @param string $orderByDesc Название поля, по которому будет происходить сортировка по убыванию (если указано).
     *
     * @return array{
     *     data: Collection,
     *     meta: mixed|null,
     *     pagingInfo: array{
     *         limit: int,
     *         offset: int,
     *         totalCount: int
     *     }
     * } Массив с данными, метаданными и информацией о пагинации.
     */
    protected static function basePagination(Request $request, array $where = [], string $orderByDesc = ''): array
    {
        $limit = (int)($request->header('limit') ?? 3);
        $offset = (int)($request->header('offset') ?? 0);
        $query = static::query();

        $search = $request->header('search');
        if ($search) {
            $search = base64_decode($search);
            $query = static::_search($query, $search);
        }

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
