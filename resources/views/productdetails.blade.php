<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
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
        .product-details {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .product-image {
            width: 50%;
        }
        .product-image img {
            max-width: 50%;
            height: auto;
        }

        .product-info {
            max-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-info h2 {
            font-size: 24px;
            margin: 0;
        }

        .product-info p {
            font-size: 16px;
            margin: 0;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        select, input[type="checkbox"], button {
            font-size: 14px;
            padding: 10px;
            margin-bottom: 15px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <h1>Product Details</h1>

    <div class="product-details">
        <div class="product-image">
            <img src="{{ $product->image }}" alt="{{ $product->title }}" style="width: 48%;">
        </div>
        <div class="product-info">
            <h2>{{ $product->title }}</h2>
            <p>{{ $product->description }}</p>
            <p>Price: ${{ $product->price }}</p>

            <!-- Subscription Options -->
            <label for="subscription">Choose a Subscription:</label>
            <select id="subscription" name="subscription">
                @foreach($subscriptions as $subscription)
                    <option value="{{ $subscription }}">{{ $subscription }}</option>
                @endforeach
            </select>

            <!-- Buy Now Button -->
            <button id="buy-now">Buy Now</button>
        </div>
    </div>
    <script>
        document.getElementById('buy-now').addEventListener('click', function() {
            // Get selected subscription value
            var selectedSubscription = document.getElementById('subscription').value;

            // Redirect to checkout page and pass subscription and product info
            var productName = "{{ $product->title }}";
            var productPrice = "{{ $product->price }}";

            window.location.href = "/checkout?product=" + encodeURIComponent(productName) + 
                                "&price=" + encodeURIComponent(productPrice) + 
                                "&subscription=" + encodeURIComponent(selectedSubscription);
        });
    </script>
</body>
</html>
