<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Middleware\JwtMiddleware;
use Modules\Auth\Http\Middleware\IsAdminMiddleware;
use Modules\Inventory\Actions\AddProductAction;
use Modules\Inventory\Actions\DeleteProductAction;
use Modules\Inventory\Actions\ListProductsAction;
use Modules\Inventory\Http\Controllers\InventoryController;


Route::prefix('v1')->group(function () {
    Route::middleware("auth:api")->group(function () {
        Route::get('inventory/products/list', ListProductsAction::class)->name('inventory.products.list');
        Route::get('inventory/products/{id}', ListProductsAction::class)->name('inventory.products.view');

        Route::middleware(IsAdminMiddleware::class)->group(function () {
            Route::post('inventory/products/add', AddProductAction::class)->name('inventory.products.add');
            Route::delete('inventory/products/{id}', DeleteProductAction::class)->name('inventory.products.delete');
        });
    });
});
