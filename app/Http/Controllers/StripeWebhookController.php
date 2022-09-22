<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function __invoke()
    {
        \Stripe\Stripe::setApiKey(config('stripe.test_secret_key'));

        // If you are testing your webhook locally with the Stripe CLI you
        // can find the endpoint's secret by running `stripe listen`
        // Otherwise, find your endpoint's secret in your webhook settings in the Developer Dashboard
        $endpoint_secret = config('stripe.test_endpoint_secret');

        $payload = @file_get_contents('php://input');
        if(!empty($_SERVER['HTTP_STRIPE_SIGNATURE'])) {
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        }
        $event = null;

        try {
            if(!empty($endpoint_secret) && !empty($sig_header)) {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } else {
                $event = \Stripe\Event::constructFrom(
                    json_decode($payload, true)
                );
            }
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Invalid payload', 400, []);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return new Response('Invalid signature', 400, []);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':

                $paymentIntent = $event->data->object;

                $payment = Payment::where('payment_intent', $paymentIntent->id)->first();

                if($payment) {
                    $payment = Payment::updateOrCreate([
                        'id' => $payment->id,
                    ], [
                        'payment_status' => Payment::STATUS_PAID,
                    ]);

                    $message = "Le paiement est terminé";
                    return new Response($message, 200, []);
                } else {
                    $message = "Aucune tentative de paiement n'a été faite";
                    return new Response($message, 400, []);
                }

                break;

            case 'payment_intent.payment_failed':

                $paymentIntent = $event->data->object;

                $payment = Payment::where('payment_intent', $paymentIntent->id)->first();

                if($payment) {
                    // See https://stripe.com/docs/error-codes
                    if($paymentIntent->last_payment_error->code === 'card_declined') {
                        // See https://stripe.com/docs/declines/codes
                    } else {

                    }

                    $message = $paymentIntent->last_payment_error->message;

                    $payment = Payment::updateOrCreate([
                        'id' => $payment->id,
                    ], [
                        'payment_status' => Payment::STATUS_CANCELED,
                        'payment_error' => $message,
                    ]);
                }

                return new Response($message, 400, []);
                break;

            case 'payment_method.attached':
                $paymentMethod = $event->data->object;
                break;

            default:
                // Unexpected event type
                $message = "Evènement inconnu";
                return new Response($message, 400, []);
        }
    }
}
