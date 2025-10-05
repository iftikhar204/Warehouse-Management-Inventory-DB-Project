<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;

Route::get('inventory/login', [InventoryController::class, 'login'])->name('login');
Route::post('inventory/signup', [InventoryController::class, 'signup'])->name('register');

Route::middleware('auth')->group(function () {
   Route::get('/', [DashboardController::class, 'index'])->name('home');
   Route::get('/warehouses/{id}/overview', [WarehouseController::class, 'overview'])->name('warehouses.overview');
   Route::resource('warehouses', WarehouseController::class);

    Route::get('/products/generate-barcode', [ProductController::class, 'generateBarcode'])->name('products.generateBarcode');
    Route::resource('products', ProductController::class);
    Route::get('/barcode/generate', [ProductController::class, 'generateBarcode'])->name('products.generate-barcode');

    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/process', [OrderController::class, 'process'])->name('orders.process');
    Route::post('orders/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    Route::get('orders.show/{id}', [OrderController::class, 'show'])->name('orders.show'); // Adjust this to orders/{order} and use route model binding
    Route::post('/orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::post('inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

    Route::resource('employees', EmployeeController::class);
    Route::put('/employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->name('employees.terminate');
    Route::put('/employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])->name('employees.reactivate');
    // Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy'); // Covered by resource
    // Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update'); // Covered by resource
    Route::get('/employees/{employee}/details', [EmployeeController::class, 'showDetails'])->name('employees.details');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__ . '/auth.php';
