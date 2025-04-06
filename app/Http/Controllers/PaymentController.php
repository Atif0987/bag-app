<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent()
    {
        
        Stripe::setApiKey(config('services.stripe.secret'));

       
        $paymentIntent = PaymentIntent::create([
            'amount' => 1000, 
            'currency' => 'usd',
        ]);

        
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }
}
