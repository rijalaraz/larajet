<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\CartRepository;
use Inertia\Inertia;
use App\Models\User;

class StripeCheckoutController extends Controller
{
    public function create()
    {
        return Inertia::render('Checkout/Create');
    }

    public function paymentIntent()
    {
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey(config('stripe.test_secret_key'));

        $cartTotal = (new CartRepository())->total();

        header('Content-Type: application/json');

        try {
            // Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $cartTotal,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'order_items' => (new CartRepository())->jsonOrderItems()
                ],
            ]);

            /** @var User $user */
            $user = auth()->user();

            $order = $user->orders()->create([
                'order_number' => uniqid()
            ]);

            /** @var Order $order */
            $payment = $order->payment()->create([
                'amount' =>  $cartTotal,
                'currency' => 'eur',
                'payment_intent' => $paymentIntent->client_secret,
                'payment_status' => Payment::STATUS_WAITING,
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

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (\Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
