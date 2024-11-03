<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view("orders.orders");
});

Route::post('/api/orders', [OrderController::class, 'createOrder']);
