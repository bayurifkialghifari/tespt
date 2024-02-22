<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'api.v1.',
    'middleware' => ['api', 'auth:sanctum'],
], function () {
    // Upload image service
    Route::post('/service/upload-image', [App\Http\Controllers\Api\V1\ImageUploadServiceController::class, 'upload'])->name('service.image.upload');

    // Get goods
    Route::get('/goods', [App\Http\Controllers\Api\V1\GoodsController::class, 'get'])->name('goods');
    Route::get('/goods/{id}', [App\Http\Controllers\Api\V1\GoodsController::class, 'getDetail'])->name('goods.detail');
    Route::post('/goods', [App\Http\Controllers\Api\V1\GoodsController::class, 'create'])->name('goods.create');
    Route::put('/goods/{id}', [App\Http\Controllers\Api\V1\GoodsController::class, 'update'])->name('goods.update');
    Route::delete('/goods/{id}', [App\Http\Controllers\Api\V1\GoodsController::class, 'delete'])->name('goods.delete');
    Route::get('/good-like', [App\Http\Controllers\Api\V1\GoodsController::class, 'getListGoods'])->name('goods.like');

    // Get purchases
    Route::get('/purchase', [App\Http\Controllers\Api\V1\PurchaseController::class, 'get'])->name('purchase');
    Route::get('/purchase/{id}', [App\Http\Controllers\Api\V1\PurchaseController::class, 'getDetail'])->name('purchase.detail');
    Route::post('/purchase', [App\Http\Controllers\Api\V1\PurchaseController::class, 'create'])->name('purchase.create');
    Route::delete('/purchase/{id}', [App\Http\Controllers\Api\V1\PurchaseController::class, 'delete'])->name('purchase.delete');

    // Purchase Details
    Route::get('/purchase-detail/{id}', [App\Http\Controllers\Api\V1\PurchaseDetailController::class, 'getDetail'])->name('purchase-detail.detail');
    Route::post('/purchase-detail', [App\Http\Controllers\Api\V1\PurchaseDetailController::class, 'create'])->name('purchase-detail.create');
    Route::put('/purchase-detail/{id}', [App\Http\Controllers\Api\V1\PurchaseDetailController::class, 'update'])->name('purchase-detail.update');
    Route::delete('/purchase-detail/{id}', [App\Http\Controllers\Api\V1\PurchaseDetailController::class, 'delete'])->name('purchase-detail.delete');

    // Get purchase approval
    Route::get('/purchase-approval', [App\Http\Controllers\Api\V1\PurchaseApprovalController::class, 'get'])->name('purchase-approval');
    Route::post('/purchase-approval', [App\Http\Controllers\Api\V1\PurchaseApprovalController::class, 'changeStatus'])->name('purchase-approval.change-status');
});
