<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeCheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('products/count', [CartController::class, 'count'])->name('products.count');
    Route::apiResource('products', CartController::class);

    Route::post('products/{id}/increase', [CartController::class, 'increase'])->name('product.increase');
    Route::post('products/{id}/decrease', [CartController::class, 'decrease'])->name('product.decrease');

    Route::post('/paymentIntent', [StripeCheckoutController::class, 'paymentIntent'])->name('payment.intent');

    Route::post('/saveOrder', OrderController::class)->name('orders.save');
});