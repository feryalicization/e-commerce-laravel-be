<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use L5Swagger\Http\Controllers\SwaggerController;
use L5Swagger\Http\Controllers\SwaggerAssetController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::get('/api/documentation', [SwaggerController::class, 'api']);
Route::get('/docs/asset/{asset}', [SwaggerAssetController::class, 'index']);
Route::get('/docs', [SwaggerController::class, 'docs']);
Route::get('/api/oauth2-callback', [SwaggerController::class, 'oauth2Callback']);




  



