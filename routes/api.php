<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ContactController;

Route::get('/image/{image}', [ImageController::class, 'get']);
Route::post('/image', [ImageController::class, 'add']);

Route::prefix('vacancy')->group(function () {
    Route::get('/{vacancy}', [VacancyController::class, 'get']);
    Route::get('/', [VacancyController::class, 'all']);
});

Route::prefix('contact')->group(function () {
    Route::get('/{contact}', [ContactController::class, 'get']);
    Route::get('/', [ContactController::class, 'all']);
});
