<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/category', App\Http\Controllers\Api\CategoryController::class);
Route::apiResource('/products', App\Http\Controllers\Api\ProductController::class);