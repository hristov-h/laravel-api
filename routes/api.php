<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;




Route::middleware('auth:sanctum')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/products/{id}/edit', [ProductController::class, 'update']);
    Route::delete('/products/{id}/delete', [ProductController::class, 'delete']);
    Route::post('/products/{categoryName}', [ProductController::class, 'getProductsByCategory']);


    
});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

});

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);