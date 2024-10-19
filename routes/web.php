<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('layouts.app');
})->name('dashboard');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('/categories', CategoryController::class);
Route::resource('/providers', ProviderController::class);
Route::resource('/warehouses', WarehouseController::class);
Route::resource('/customers', CustomerController::class);
