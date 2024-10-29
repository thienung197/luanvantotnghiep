<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Employee\GoodsIssueController;
use App\Http\Controllers\Employee\GoodsReceiptController;
use App\Models\AttributeValue;
use App\Models\Customer;
use App\Models\GoodsReceipt;
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
Route::resource('/attributes', AttributeController::class);
Route::get('edit-attribute/{id}', [AttributeController::class, 'editAttribute']);
Route::post('/attributes/update/{id}', [AttributeController::class, 'updateAttribute']);
Route::delete('attributes/{attribute}/values/{value}', [AttributeController::class, 'destroyValues'])->name('attributes.destroyValues');
Route::post('/attributes/{attribute}', [AttributeController::class, 'storeValue'])->name('attributes.storeValue');
Route::get('/attribute/{attribute}/edit/{attributeValue}', [AttributeController::class, 'editAttributeValue']);
Route::post('/attribute/{attribute}/update/{attributeValue}', [AttributeController::class, 'updateAttributeValue']);
Route::resource('/products', ProductController::class);
Route::resource('/goodsreceipts', GoodsReceiptController::class);
Route::resource('/goodsissues', GoodsIssueController::class);
Route::get('/ajax-search-product', [ApiController::class, 'ajaxSearchProduct'])->name('ajax-search-product');
Route::get('/fetch-batches', [ApiController::class, 'getBatches'])->name('fetch-batches');
Route::get('/ajax-search-batch', [ApiController::class, 'ajaxSearchBatch'])->name('ajax-search-batch');
