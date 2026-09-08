<?php

namespace App\Models;

use App\Http\Requests\PaginationRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Возвращает коллекцию данных ответа.
     *
     * @param Model | Collection $data Коллекция данных для обработки
     * @return Model | Collection Исходная коллекция данных
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _response_data(Model|Collection $data): Model|Collection
    {
        return $data;
    }


    /**
     * Формирует стандартизированный ответ API с данными и информацией о пагинации.
     *
     * @param Model | Collection $data Коллекция элементов текущей страницы
     * @param mixed $meta Дополнительные метаданные ответа
     * @param int $offset Смещение от начала набора данных
     * @param int $totalCount Общее количество записей
     *
     * @return array{
     *     data: Model | Collection,
     *     meta: mixed,
     *     pagingInfo: array{
     *         limit: int,
     *         offset: int,
     *         totalCount: int
     *     }
     * }
     */
    #[\NoDiscard("The name must not be discarded")]
    private static function _response(Model|Collection $data, mixed $meta, int $offset, int $totalCount): array
    {
        $data = static::_response_data($data);
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

    /**
     * Хук, вызываемый после получения модели из базы данных.
     *
     * @return static
     */
    protected function _after_get()
    {
        return $this;
    }

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
        $data->_after_get();

        if (static::$isTree) {
            $data->tree = static::where('self_id', $data->id)->get();
        }

        return static::_response_data($data);
    }

    /**
     * Внутренний метод для применения фильтров к запросу.
     * Может быть переопределен в дочерних классах для реализации специфической фильтрации.
     *
     * @param Builder $query Конструктор запроса Eloquent.
     * @param Request $request Фильтры, полученные из запроса (обычно JSON-декодированные).
     * @return Builder Модифицированный конструктор запроса.
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _filter($query, $request)
    {
        return $query;
    }

    /**
     * Внутренний метод для получения метаданных на основе переданного запроса.
     * Может быть переопределён в дочерних классах для возврата дополнительных мета-данных.
     *
     * @param Builder $query Построитель запросов с применёнными фильтрами.
     * @return array<string, mixed>|null Метаданные или null, если не определены.
     */
    protected static function _meta(PaginationRequest $request)
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
     * Преобразует строку с разделителями-запятыми в очищенный массив.
     *
     * Разбивает строку по запятым, удаляет пробелы по краям каждого элемента
     * и отфильтровывает пустые значения. Результат не должен быть проигнорирован:
     * возвращаемый массив содержит значимые данные.
     *
     * @param string $string Входная строка вида "foo, bar, baz"
     *
     * @return array Массив непустых, обрезанных строк
     *
     * @example
     * self::stringToArray('apple, , banana, cherry')
     * // => ['apple', 'banana', 'cherry']
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function stringToArray(string $string): array
    {
        return explode(',', $string)
                |> (fn($x) => array_map('trim', $x))
                |> (fn($x) => array_filter($x));
    }

    /**
     * Применяет сортировку к запросу на основе указанного поля и направления.
     *
     * Если переданное поле присутствует в списке разрешённых для сортировки полей
     * {@see static::$sortable}, выполняется сортировка по нему в указанном направлении.
     * В противном случае применяется сортировка по убыванию поля `updated_at`.
     *
     * @param Builder $query Экземпляр построителя запросов Eloquent.
     * @param string $order Название поля, по которому выполняется сортировка.
     * @param bool $desc Направление сортировки: {@see true} — по убыванию, {@see false} — по возрастанию.
     *
     * @return Builder Построитель запросов с применённой сортировкой.
     *
     * @see static::$sortable Список допустимых полей для сортировки.
     */
    #[\NoDiscard("The name must not be discarded")]
    protected static function _orderBy(Builder $query, string $order, bool $desc): Builder
    {
        if (in_array($order, static::$sortable)) return $query->orderBy($order, $desc ? 'desc' : 'asc');
        else return $query->orderByDesc('created_at');

    }

    /**
     * @param Builder $query Экземпляр построителя запросов Eloquent.
     * @return Builder Модифицированный конструктор запроса.
     */
    protected static function _after_basePagination(Builder $query)
    {
        return $query;
    }

    /**
     * Базовая пагинация по заданному условию.
     *
     * Метод обрабатывает пагинацию, фильтрацию, поиск и сортировку на основе параметров HTTP-запроса.
     * Поддерживает фильтры, поиск, игнорирование ID, сортировку и метаданные.
     *
     * @param PaginationRequest $request HTTP-запрос, из которого берутся limit, offset, search, filters и другие параметры.
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
    protected static function basePagination(PaginationRequest $request): array
    {
        [
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'order' => $order,
            'desc' => $desc,
            'names' => $names
        ] = $request->validated();

//        Log::channel('daily')->debug('=== START PRODUCT REQUEST ===', [
//            'full_url' => $request->fullUrl(),
//            'all_params' => $request->all(),
//            'ip' => $request->ip(),
//            'user_agent' => $request->header('User-Agent'),
//        ]);

        $query = static::query()
                |> (fn($x) => static::_search($x, $search))
                |> (fn($x) => static::_filter($x, $request))
                |> (fn($x) => static::$isTree ? $x->where('self_id', null) : $x)
                |> (fn($x) => static::_orderBy($x, $order, $desc))
                |> (fn($x) => static::_after_basePagination($x));

//        Log::channel('daily')->debug('Built SQL BEFORE execution', [
//            'sql' => $query->toSql(),
//            'bindings' => $query->getBindings(),
//            'bindings_count' => count($query->getBindings()),
//        ]);

        DB::enableQueryLog();

        $totalCount = $query->count();

        if ($names) $query->whereIn('name', static::stringToArray($names));
        else $query->offset($offset)->limit($limit);

        $meta = static::_meta($request);
        $data = $query->get();

//        Log::channel('daily')->debug('Query executed', [
//            'query_log' => DB::getQueryLog(),
//            'results_count' => $totalCount,
//        ]);
//
//        Log::channel('daily')->debug('=== END PRODUCT REQUEST ===', [
//            'memory' => memory_get_peak_usage(true) / 1024 / 1024 . ' MB',
//            'time' => microtime(true) - LARAVEL_START,
//        ]);

        return static::_response(
            data: $data,
            meta: $meta,
            offset: $offset,
            totalCount: $totalCount,
        );
    }
}
