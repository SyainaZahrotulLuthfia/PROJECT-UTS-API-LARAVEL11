<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// route level resource
// Route::resource('level', App\http\Controllers\Api\LevelController::class);

Route::prefix('v1')->group( function () {
    //Route untuk register user
        Route::post('auth/register', \App\Http\Controllers\Api\Auth\RegisterController::class);
    //Route untuk Login user
        Route::post('auth/login', \App\Http\Controllers\Api\Auth\LoginController::class);
    //
    Route::resource('home', \App\Http\Controllers\Api\HomeController::class)->except(['edit']);


        // Route yang hanya bisa diakses dengan token
        Route::middleware('auth:sanctum')->group(function () {
            //Route untuk Logout user
           Route::post('auth/logout', \App\Http\Controllers\Api\Auth\LogoutController::class);

        Route::resource('categories', \App\Http\Controllers\Api\CategoryController::class)->except(['edit']);
        Route::resource('product', \App\Http\Controllers\Api\ProductController::class)->except(['edit']);
        });

});
