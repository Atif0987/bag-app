<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderReceipt;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Subscription;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $product = $request->input('product');
        $price = $request->input('price');
        $subscription = $request->input('subscription');

        return view('checkout', compact('product', 'price', 'subscription'));
    }
    public function process(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'product' => 'required',
            'price' => 'required|numeric',
            'subscription' => 'required'
        ]);
        try {
            Stripe::setApiKey('sk_test_51NHtioI7D8hVACYM6d9SvOYal70d4aw7jCvBzRjWeMzYyKb9HvmereVJLXRUSAshLAk3cy9o4qQRsyF3gENLxZfR00NPEc1YDR');

            
            $charge = Charge::create([
                'amount' => $request->price * 100, 
                'currency' => 'usd',
                'description' => 'Bag Rental: ' . $request->product,
                'source' => $request->token,
                'metadata' => [
                    'subscription_plan' => $request->subscription . ' months',
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_address' => $request->address,
                ]
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }
    public function processSubscription(Request $request)
{
    // Stripe::setApiKey(env('STRIPE_SECRET'));
    Stripe::setApiKey('sk_test_51NHtioI7D8hVACYM6d9SvOYal70d4aw7jCvBzRjWeMzYyKb9HvmereVJLXRUSAshLAk3cy9o4qQRsyF3gENLxZfR00NPEc1YDR');


    // Create Stripe Customer
    $customer = Customer::create([
        'email' => $request->email,
        'name' => $request->name,
        'address' => ['line1' => $request->address],
        'payment_method' => $request->payment_method,
        'invoice_settings' => ['default_payment_method' => $request->payment_method],
    ]);

    // Create Stripe Subscription (replace 'price_xxx' with your actual Stripe price ID)
    $subscription = Subscription::create([
        'customer' => $customer->id,
        'items' => [[ 'price' => $request->stripe_price_id ]],
        'expand' => ['latest_invoice.payment_intent'],
    ]);

    $amount = $request->amount;
    $subscriptionId = $subscription->id;

   
    $order = Order::create([
        'name' => $request->name,
        'email' => $request->email,
        'address' => $request->address,
        'product_name' => $request->product,
        'subscription_plan' => $request->subscription,
        'amount' => $amount,
        'stripe_subscription_id' => $subscriptionId,
    ]);

    
    Mail::to($request->email)->send(new OrderReceipt($order));

    
    return redirect()->route('thankyou')->with('message', 'Subscription successful!');
}

}
