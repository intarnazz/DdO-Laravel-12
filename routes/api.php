<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BrandController;
use App\Route\GetRoute;


Route::get('/image/{image}', [ImageController::class, 'get']);
Route::post('/image', [ImageController::class, 'add']);

new GetRoute('vacancy', VacancyController::class);
new GetRoute('contact', ContactController::class);
new GetRoute('brand', BrandController::class);

