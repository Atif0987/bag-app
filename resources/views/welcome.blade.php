<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bag Rental App</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1, h2 {
            text-align: center;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }

        .product {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 15px;
            width: 19%;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .product:hover {
            transform: translateY(-10px);
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product h3 {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .product p {
            font-size: 1rem;
            margin: 10px 0;
        }

        .product p.price {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .buy-now {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .buy-now:hover {
            background-color: #2980b9;
        }

        #payment-form {
            display: none;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin: 20px auto;
            text-align: center;
        }

        #submit {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
        }

        #submit:hover {
            background-color: #27ae60;
        }

        #error-message {
            color: red;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Bag Rental App</h1>
    <h2>Our Products</h2>

    <div class="products">
        @foreach($products as $product)
            <div class="product">
                <img src="{{ $product->image }}" alt="{{ $product->title }}" style="width: 150px;">
                <h3>{{ $product->title }}</h3>
                <p>{{ $product->description }}</p>
                <p>Price: ${{ $product->price }}</p>

                <!-- View Details Button -->
                <button class="view-details" id="view-details-{{ $product->id }}" data-product-id="{{ $product->id }}">
                    <a href="{{ route('product.details', ['product_id' => $product->id]) }}">View Details</a>
                </button>
                <!-- <a href="{{ route('product.details', ['product_id' => $product->id]) }}">View Details</a> -->
            </div>
        @endforeach
    </div>

    <!-- Stripe Payment Form (will be dynamically handled) -->
    <div id="payment-form">
        <h3>Complete Your Payment</h3>
        <div id="card-element"></div> <!-- A Stripe Element will be inserted here. -->
        <button id="submit">Pay Now</button>
        <div id="error-message"></div>
    </div>

    <script>
        // Initialize Stripe
        var stripe = Stripe('pk_test_51NHtioI7D8hVACYMGNJNInarAq3XvUTkGQh6fABjcisQMDKE3eA6myuCZK4WAmz2tAfu3m3vG8lZAYvR6GbpV20600uHHIDjAh'); // Replace with your public Stripe key
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        var paymentForm = document.getElementById('payment-form');
        var errorMessage = document.getElementById('error-message');
        var submitButton = document.getElementById('submit');
        var productId = null;

        // Handle Buy Now button click
        document.querySelectorAll('.buy-now').forEach(function (button) {
            button.addEventListener('click', function () {
                productId = this.getAttribute('data-product-id');
                var price = this.getAttribute('data-price');

                // Show the payment form
                paymentForm.style.display = 'block';
                stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                }).then(function(result) {
                    if (result.error) {
                        errorMessage.textContent = result.error.message;
                        errorMessage.style.display = 'block';
                    } else {
                        // Handle payment request
                        fetch('/create-payment-intent', {
                            method: 'POST',
                            body: JSON.stringify({ amount: price * 100 }), // Convert to cents
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            stripe.confirmCardPayment(data.clientSecret, {
                                payment_method: result.paymentMethod.id
                            }).then(function(paymentResult) {
                                if (paymentResult.error) {
                                    errorMessage.textContent = paymentResult.error.message;
                                    errorMessage.style.display = 'block';
                                } else {
                                    alert('Payment Successful!');
                                    paymentForm.style.display = 'none';
                                }
                            });
                        });
                    }
                });
            });
        });

        // Attach card element to the page
        cardElement.mount('#card-element');
    </script>
</body>
</html>
