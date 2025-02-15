<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use L5Swagger\Http\Controllers\SwaggerController;
use L5Swagger\Http\Controllers\SwaggerAssetController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::get('/api/documentation', [SwaggerController::class, 'api']);
Route::get('/docs/asset/{asset}', [SwaggerAssetController::class, 'index']);
Route::get('/docs', [SwaggerController::class, 'docs']);
Route::get('/api/oauth2-callback', [SwaggerController::class, 'oauth2Callback']);


Route::middleware('auth:sanctum')->group(function () {
    // products
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Category 
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
});




  



