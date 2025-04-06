<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProducts()
    {
        $client = new Client();
        $response = $client->get('https://fakestoreapi.com/products'); 
        $products = json_decode($response->getBody()->getContents());
        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['id' => $productData->id], 
                [
                    'title' => $productData->title,
                    'description' => $productData->description,
                    'price' => $productData->price,
                    'image' => $productData->image,
                ]
            );
        }

        return view('welcome', compact('products')); 
    }
    public function showProductDetails($product_id)
    {
        $product = Product::findOrFail($product_id);
        $subscriptions = ['3 Months', '6 Months', '9 Months']; 
        return view('productdetails', compact('product', 'subscriptions'));
    }
    public function checkout($product_id)
    {
        $client = new Client();
        $response = $client->get("https://fakestoreapi.com/products/{$product_id}");
        $product = json_decode($response->getBody()->getContents());

        return view('checkout', compact('product'));
    }
    public function processPayment(Request $request)
    {
    
        Stripe::setApiKey(env('STRIPE_SECRET'));

        
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Bag Rental - ' . $request->product_id,
                        ],
                        'unit_amount' => 1000, 
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'subscription',
            'subscription_data' => [
                'items' => [
                    [
                        'plan' => env('STRIPE_PLAN_ID'),
                    ],
                ],
            ],
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

}
