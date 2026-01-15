<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Actions\LoginAction;
use Modules\Auth\Actions\RegisterAction;
use Modules\Auth\Actions\HeartbeatAction;


Route::prefix('auth')->group(function () {
    Route::get('heartbeat', HeartbeatAction::class);    
    Route::post('customer/register', RegisterAction::class)->name('customer.register');
    Route::post('login', LoginAction::class);

    Route::middleware('auth:api')->group(function () {
        Route::post('admin/register', RegisterAction::class)->name('admin.register');
    });
});