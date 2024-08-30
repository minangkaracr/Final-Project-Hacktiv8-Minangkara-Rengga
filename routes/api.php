<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware'=>'api'], function(){
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('me', [AuthController::class,'me']);
});

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

// Route::resource('categories', CategoryController::class)->middleware('auth');
Route::get('categories', [CategoryController::class, 'index']);
Route::get('products', [ProductController::class, 'index']);
Route::resource('categories', CategoryController::class)->except(['index'])->middleware('auth');
Route::resource('products', ProductController::class)->except(['index'])->middleware('auth');
Route::resource('orders', OrderController::class)->middleware('auth');
Route::get('/report', [OrderController::class, 'report']);