<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'cms',
    'as' => 'cms.',
    'middleware' => ['auth', 'validate-role-permission'],
], function () {

    Route::get('/', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/goods', fn () => view('cms.goods.index'))->name('goods');

    // Purchase
    Route::get('/purchase', fn () => view('cms.purchase.index'))->name('purchase');
    Route::get('/purchase/detail/{id?}', fn () => view('cms.purchase.detail'))->name('purchase.detail');
    Route::get('/purchase-approval', fn () => view('cms.purchase-approval.index'))->name('purchase.approval');
    Route::get('/purchase-approval/{id?}', fn () => view('cms.purchase-approval.detail'))->name('purchase.approval.detail');

    // Sell
    Route::get('/sell', fn () => view('cms.sell.index'))->name('sell');
    Route::get('/sell/detail/{id?}', fn () => view('cms.sell.detail'))->name('sell.detail');
    Route::get('/sell-approval', fn () => view('cms.sell-approval.index'))->name('sell.approval');
    Route::get('/sell-approval/{id?}', fn () => view('cms.sell-approval.detail'))->name('sell.approval.detail');


    // Management
    Route::get('/management/menu', App\Livewire\Cms\Management\Menu::class)->name('management.menu');
    Route::get('/management/role', App\Livewire\Cms\Management\Role::class)->name('management.role');
    Route::get('/management/role-permission/{role?}', App\Livewire\Cms\Management\RolePermission::class)->name('management.role-permission');
    Route::get('/management/user', App\Livewire\Cms\Management\User::class)->name('management.user');
    Route::get('/management/website', App\Livewire\Cms\Management\Setting::class)->name('management.setting');
});
