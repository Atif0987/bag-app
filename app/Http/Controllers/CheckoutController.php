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

             // Create the order and associate with the logged-in user
            $order = Order::create([
                'user_id' => auth()->user()->id, // Store user_id in the order
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'product_name' => $request->product,
                'subscription_plan' => $request->subscription,
                'amount' => $request->price,
                'stripe_subscription_id' => null, // No subscription for one-time payment
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
    Stripe::setApiKey('sk_test_51NHtioI7D8hVACYM6d9SvOYal70d4aw7jCvBzRjWeMzYyKb9HvmereVJLXRUSAshLAk3cy9o4qQRsyF3gENLxZfR00NPEc1YDR');

    // Create Stripe Customer
    $customer = \Stripe\Customer::create([
        'email' => $request->email,
        'name' => $request->name,
        'address' => ['line1' => $request->address],
        'payment_method' => $request->payment_method,
        'invoice_settings' => ['default_payment_method' => $request->payment_method],
    ]);

    // Create Stripe Subscription (replace 'price_xxx' with your actual Stripe price ID)
    $stripeSubscription = \Stripe\Subscription::create([
        'customer' => $customer->id,
        'items' => [['price' => $request->stripe_price_id]],
        'expand' => ['latest_invoice.payment_intent'],
    ]);

    // Get the Stripe subscription ID
    $stripeSubscriptionId = $stripeSubscription->id;

    // Get subscription details (amount and other order details)
    $amount = $request->amount;

    // Create the order and associate with the logged-in user
    $order = Order::create([
        'user_id' => auth()->user()->id, // Store user_id in the order
        'name' => $request->name,
        'email' => $request->email,
        'address' => $request->address,
        'product_name' => $request->product,
        'subscription_plan' => $request->subscription,
        'amount' => $amount,
        'stripe_subscription_id' => $stripeSubscriptionId, // Store the Stripe subscription ID here
    ]);

    // Create a subscription record in your 'subscriptions' table
    $subscriptionRecord = Subscription::create([
        'user_id' => auth()->user()->id,
        'plan' => $request->subscription,  // '3_months', '6_months', or '9_months'
        'start_date' => now(),
        'end_date' => now()->addMonths($this->getPlanDuration($request->subscription)),
        'is_active' => true,
    ]);

    // Send order receipt email
    Mail::to($request->email)->send(new OrderReceipt($order));

    return redirect()->route('thankyou')->with('message', 'Subscription successful!');
}

private function getPlanDuration($plan)
{
    switch ($plan) {
        case '3_months':
            return 3;
        case '6_months':
            return 6;
        case '9_months':
            return 9;
        default:
            return 0;
    }
}


}
