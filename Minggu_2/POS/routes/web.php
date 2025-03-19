<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;

Route::get('/home', [HomeController::class, 'index']);

Route::get('/user/{id}/{name}', [UserController::class, 'show'])->name('user.show');

Route::get('/product', [ProductsController::class, 'index'])->name('product');

Route::prefix('category')->group(function () {
    Route::get('/{cat}', [ProductsController::class, 'show'])->name('daftar');
});

Route::get('/sales/{month}/{date}', [SalesController::class, 'show'])->name('sales.show');