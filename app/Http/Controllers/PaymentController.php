<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent()
    {
        // Set the Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        // Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => 1000, // Amount in cents ($10.00)
            'currency' => 'usd',
        ]);

        // Return the client secret to the front-end for Stripe.js
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }
}
