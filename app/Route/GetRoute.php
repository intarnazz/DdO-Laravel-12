<?php

namespace App\Route;

use Illuminate\Support\Facades\Route;

/**
 * Class GetRoute
 *
 * Утилитный класс для быстрого создания стандартных GET-маршрутов
 * с общим префиксом.
 *
 * Создаёт два маршрута:
 *  - GET /{prefix}        → Controller@all
 *  - GET /{prefix}/{id}   → Controller@get
 *
 * Пример использования:
 *
 *  new GetRoute('vacancy', VacancyController::class);
 *  new GetRoute('contact', ContactController::class);
 */
class GetRoute
{
    /**
     * Регистрирует группу GET-маршрутов с указанным префиксом и контроллером.
     *
     * @param string $prefix
     *        URL-префикс для группы маршрутов
     *        (например: "vacancy", "contact")
     *
     * @param string $controller
     *        Полное имя класса контроллера.
     *        Контроллер должен содержать методы:
     *        - all() — для получения списка ресурсов
     *        - get($id) — для получения одного ресурса
     *
     * @return void
     */
    public function __construct(string $prefix, string $controller)
    {
        Route::prefix($prefix)->group(function () use ($controller) {
            Route::get('/', [$controller, 'all']);
            Route::get('/{id}', [$controller, 'get']);
        });
    }
}
