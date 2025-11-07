<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\CalculatorController;
use Brick\Math\Internal\Calculator;

Route::get('/', [\App\Http\Controllers\LoginController::class, 'index']);
Route::get('login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');

Route::post('action-login', [\App\Http\Controllers\LoginController::class, 'actionLogin'])->name('action-login');
Route::get('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::resource('dashboard', \App\Http\Controllers\DashboardController::class);
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::resource('category', \App\Http\Controllers\CategoriesController::class);
    Route::resource('role', \App\Http\Controllers\RoleController::class);
    Route::resource('product', \App\Http\Controllers\ProductController::class);
    Route::resource('profile', \App\Http\Controllers\ProfileController::class);
    Route::resource('order', \App\Http\Controllers\OrderController::class);
    Route::post('change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])
        ->name('profile.change-password');
    Route::post('change-profile', [\App\Http\Controllers\ProfileController::class, 'changeProfile'])
        ->name('profile.change-profile');

    Route::get('get-products', [\App\Http\Controllers\OrderController::class, 'getProducts'])
        ->name('get-products');

    Route::post('cashless', [\App\Http\Controllers\OrderController::class, 'paymentCashless'])
        ->name('cashless');
});


Route::get('belajar', [\App\Http\Controllers\BelajarController::class, 'index'])->name('belajar.index');
Route::get('belajar/tambah', [\App\Http\Controllers\BelajarController::class, 'tambah'])
    ->name('belajar.tambah');
Route::post('storeTambah', [\App\Http\Controllers\BelajarController::class, 'storeTambah'])
    ->name('storeTambah');

Route::get('calculator', [CalculatorController::class, 'create']);
Route::post('calculator/store', [CalculatorController::class, 'store'])->name('calculator.store');





// get: preview / menampilkan
// post: mengirim sebuah data melalui form
// put: mengirim sebuah data melalui form tapi update
// delete: mengirim sebuah data melalui form tapi hapus
