<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\ProductController;
use App\Route\GetRoute;


Route::get('/image/{image}', [ImageController::class, 'get']);
Route::post('/image', [ImageController::class, 'add']);

new GetRoute('vacancy', VacancyController::class);
new GetRoute('contact', ContactController::class);
new GetRoute('brand', BrandController::class);
new GetRoute('filter', FilterController::class);
new GetRoute('documentation', DocumentationController::class);
new GetRoute('type', TypeController::class);
new GetRoute('tag', TagController::class);
new GetRoute('variant', VariantController::class);
new GetRoute('product', ProductController::class);

