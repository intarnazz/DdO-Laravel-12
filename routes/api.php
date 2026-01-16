<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VacancyController;

Route::get('/image/{image}', [ImageController::class, 'get']);
Route::post('/image', [ImageController::class, 'add']);

Route::get('/vacancy/{vacancy}', [VacancyController::class, 'get']);
Route::get('/vacancy', [VacancyController::class, 'all']);
