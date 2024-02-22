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
});
