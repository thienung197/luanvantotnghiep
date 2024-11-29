<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/approved-products', [ApiController::class, 'loadApprovedProducts'])->name('approved-products');
Route::post('/restock-request/{id}/status',  [ApiController::class, 'updateRestockRequestStatus'])->name('update-restock-request-status');
Route::get('/ajax-search-product', [ApiController::class, 'ajaxSearchProduct'])->name('ajax-search-product');
Route::get('/ajax-search-product-by-provider', [ApiController::class, 'ajaxSearchProductByProvider'])->name('ajax-search-product-by-provider');

Route::get('/ajax-search-product-table', [ApiController::class, 'ajaxSearchProductTable'])->name('ajax-search-product-table');
Route::get('/ajax-update-product-price', [ApiController::class, 'updateProductPrice'])->name('ajax-update-product-price');
Route::get('/ajax-search-product-by-warehouse', [ApiController::class, 'ajaxSearchProductByWarehouse'])->name('ajax-search-product-by-warehouse');
Route::get('/ajax-search-product-and-inventory-by-warehouse', [ApiController::class, 'ajaxSearchProductAndInventoryByWarehouse'])->name('ajax-search-product-and-inventory-by-warehouse');
Route::get('/ajax-search-goods-issue-batch', [ApiController::class, 'ajaxSearchGoodsIssueBatch'])->name('ajax-search-goods-issue-batch');
Route::get('/get-inventory-quantity', [ApiController::class, 'getInventoryQuantity'])->name('ajax-batch-inventory');
Route::get('/ajax-search-batch', [ApiController::class, 'ajaxSearchBatch'])->name('ajax-search-batch');
Route::get('/fetch-batches', [ApiController::class, 'getBatches'])->name('fetch-batches');
Route::get('/ajax-search-batch', [ApiController::class, 'ajaxSearchBatch'])->name('ajax-search-batch');
Route:
Route::get('/restock/suggested-products', [ApiController::class, 'getSuggestedProducts'])->name('restock.suggested-products');

Route::get('/notifications/unread/count', [NotificationController::class, 'getUnReadNotificationCount'])->name('notifications.unread.count');
Route::get('/notifications/unread', [NotificationController::class, 'getUnReadNotifications'])->name('notifications.unread');
Route::post('/notifications/read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read');

Route::get('/stock-report', [ApiController::class, 'getStockReportData'])->name('stock-report');
