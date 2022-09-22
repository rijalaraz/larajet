<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\StripeCheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [OrderController::class, 'list'])->name('dashboard');

    Route::get('/products', ProductController::class)->name('product.index');

    Route::get('/shoppingCart', ShoppingCartController::class)->name('cart.index');

    Route::get('/checkout', [StripeCheckoutController::class, 'create'])->name('checkout.create');

    Route::get('/merci', fn() => 'Merci pour votre commande')->name('order.merci');
});
