<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// User Routes
Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('create-user', [UserController::class, 'create'])->name('users.create');
    Route::post('create-user', [UserController::class, 'store'])->name('users.store');
    Route::get('edit-user/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('update-user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/archive/{id}', [UserController::class, 'archive'])->name('users.archive');
});

// Categories Route
Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('add-category', [CategoryController::class, 'create'])->name('categories.add');
    Route::post('add-category', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('edit-category/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('update-category/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('archive-category/{id}', [CategoryController::class, 'archive'])->name('categories.delete');
    Route::get('/get-custom-fields/{id}', [CategoryController::class, 'getCustomFields']);
});