<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Actions\AddOrderAction;
use Modules\Order\Actions\ListOrdersAction;
use Modules\Order\Actions\CancelOrderAction;
use Modules\Order\Actions\DeleteOrderAction;
use Modules\Order\Actions\DeleteOrderItemAction;
use Modules\Auth\Http\Middleware\IsAdminMiddleware;

Route::prefix('v1')->group(function () {
    Route::middleware("auth:api")->group(function () {
        Route::get('orders/list', ListOrdersAction::class)->name('orders.list.view');
        Route::post('orders/add', AddOrderAction::class)->name('order.add');
        Route::delete('orders/items/delete/{id}', DeleteOrderItemAction::class)->name('order.items.delete');
        Route::patch('orders/cancel/{id}', CancelOrderAction::class)->name('order.cancel');

        Route::middleware(IsAdminMiddleware::class)->group(function () {
            Route::get('orders/all', ListOrdersAction::class)->name('orders.all.view');
            Route::delete('orders/delete/{id}', DeleteOrderAction::class)->name('order.delete');
        });
    });
});

