<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/image/{image}', [ImageController::class, 'get']);
Route::post('/image', [ImageController::class, 'add']);
