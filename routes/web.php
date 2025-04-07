<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



// Route::get('/', [ProductController::class, 'showProducts'])->name('welcome');
// Route::get('/', function () {
//     return redirect()->route(Auth::check() ? 'dashboard' : 'login');
// });

Route::get('/', function () {
    if (Auth::check()) {
        return app(\App\Http\Controllers\ProductController::class)->showProducts();
    }
    return redirect()->route('login');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/product/{product_id}', [ProductController::class, 'showProductDetails'])->name('product.details');
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process']);
Route::get('/thank-you', function () {
    return view('thankyou');
});
Route::post('/process-subscription', [CheckoutController::class, 'processSubscription'])->name('process.subscription');
Route::get('/thank-you', function () {
    return view('thankyou');
})->name('thankyou');

// Route::get('/checkout/{product_id}', [ProductController::class, 'checkout'])->name('checkout');

Route::post('/process-payment', [ProductController::class, 'processPayment'])->name('process-payment');

Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);

Route::get('/payment/success', function () {
    return "Payment successful!";
});

Route::get('/payment/cancel', function () {
    return "Payment cancelled!";
});
require __DIR__.'/auth.php';
