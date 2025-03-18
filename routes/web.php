<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('create-user', [UserController::class, 'create'])->name('users.create');
    Route::post('create-user', [UserController::class, 'store'])->name('users.store');
    Route::get('edit-user/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('update-user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/archive/{id}', [UserController::class, 'archive'])->name('users.archive');
});
