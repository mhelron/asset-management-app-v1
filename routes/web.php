<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CustomFieldsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\AccessoryController;

Route::prefix('/')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

// User Routes
Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('profile/{id}', [UserController::class, 'view'])->name('users.view');
    Route::get('create-user', [UserController::class, 'create'])->name('users.create');
    Route::post('create-user', [UserController::class, 'store'])->name('users.store');
    Route::get('edit-user/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('update-user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/archive-user/{id}', [UserController::class, 'archive'])->name('users.archive');
});

// Categories Route
Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('add-category', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('add-category', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('edit-category/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('update-category/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('archive-category/{id}', [CategoryController::class, 'archive'])->name('categories.archive');
    Route::get('/get-custom-fields/{id}', [CategoryController::class, 'getCustomFields']);
});

 // Inventory Route
 Route::prefix('/inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('add-inventory', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('add-inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('edit-inventory/{id}', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('update-inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/archive-item/{id}', [InventoryController::class, 'archive'])->name('inventory.archive');
    Route::get('get-category-fields/{id}', [InventoryController::class, 'getCategoryFields'])->name('inventory.category.fields');
    Route::get('/get-item-details/{id}', [InventoryController::class, 'getItemDetails']);
    Route::get('/get-custom-fields/{id}', [InventoryController::class, 'getCustomFields']);
});

// Custom Fields Route
Route::prefix('/custom-fields')->group(function () {
    Route::get('/', [CustomFieldsController::class, 'index'])->name('customfields.index');
    Route::get('add-custom-field', [CustomFieldsController::class, 'create'])->name('customfields.create');
    Route::post('add-custom-field', [CustomFieldsController::class, 'store'])->name('customfields.store');
    Route::get('edit-custom-field/{id}', [CustomFieldsController::class, 'edit'])->name('customfields.edit');
    Route::put('update-custom-field/{id}', [CustomFieldsController::class, 'update'])->name('customfields.update');
    Route::delete('archive-custom-field/{id}', [CustomFieldsController::class, 'archive'])->name('customfields.archive');
});

// Department Routes
Route::prefix('/departments')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('create-department', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('create-department', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('edit-department/{id}', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('update-department/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/archive-department/{id}', [DepartmentController::class, 'archive'])->name('departments.archive');
});

// Component Routes
Route::prefix('/components')->group(function () {
    Route::get('/', [ComponentController::class, 'index'])->name('components.index');
    Route::get('create-component', [ComponentController::class, 'create'])->name('components.create');
    Route::get('show-component/{id}', [ComponentController::class, 'show'])->name('components.show');
    Route::post('create-component', [ComponentController::class, 'store'])->name('components.store');
    Route::get('edit-component/{id}', [ComponentController::class, 'edit'])->name('components.edit');
    Route::put('update-component/{id}', [ComponentController::class, 'update'])->name('components.update');
    Route::delete('/archive-component/{id}', [ComponentController::class, 'archive'])->name('components.archive');
});

// Component Routes
Route::prefix('/accessories')->group(function () {
    Route::get('/', [AccessoryController::class, 'index'])->name('accessory.index');
    Route::get('create-accessory', [AccessoryController::class, 'create'])->name('accessory.create');
    Route::get('show-accessory/{id}', [AccessoryController::class, 'show'])->name('accessory.show');
    Route::post('create-accessory', [AccessoryController::class, 'store'])->name('accessory.store');
    Route::get('edit-accessory/{id}', [AccessoryController::class, 'edit'])->name('accessory.edit');
    Route::put('update-accessory/{id}', [AccessoryController::class, 'update'])->name('accessory.update');
    Route::delete('/archive-accessory/{id}', [AccessoryController::class, 'archive'])->name('accessory.archive');
});