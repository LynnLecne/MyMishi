<?php

use App\Http\Controllers\Api\CatalogApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/catalog', [CatalogApiController::class, 'index'])->name('api.catalog');
Route::get('/products', [CatalogApiController::class, 'products'])->name('api.products');
