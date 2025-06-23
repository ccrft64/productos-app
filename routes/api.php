<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', [ProductApiController::class, 'index']);

Route::get('products/{product}', [ProductApiController::class, 'show']);

Route::get('categories/{category}/products', [ProductApiController::class, 'productsByCategory']);