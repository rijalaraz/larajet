<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\CartRepository;
use App\Models\User;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->user();
        $order = $user->orders()->create([
            'order_number' => uniqid()
        ]);

        (new CartRepository())
            ->content()
            ->each(function($product) use($order) {
                /** @var Order $order */
                $order->products()->attach($product->id, [
                    'total_price' => $product->price * $product->quantity,
                    'total_quantity' => $product->quantity,
                ]);
            });

        (new CartRepository())->clear();
    }

    public function list()
    {
        /** @var User $user */
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'orders' => $user->orders()->with('products')->get(),
        ]);
    }
}
