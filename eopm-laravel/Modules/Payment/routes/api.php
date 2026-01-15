<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Actions\PayAction;


Route::prefix('v1')->group(function () {
    Route::middleware("auth:api")->group(function () {
        Route::post('orders/pay', PayAction::class)->name('order.pay');
    });
});

