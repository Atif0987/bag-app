<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .checkout-page {
            display: flex;
            justify-content: space-between;
            max-width: 1000px;
            margin: 0 auto;
            gap: 40px;
        }

        .checkout-details, .product-summary {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        #card-element {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 10px;
        }

        .notice-box {
            margin-top: 20px;
            padding: 15px;
            background: #fff8e1;
            border-left: 5px solid #ffc107;
        }

        #submit-payment {
            margin-top: 20px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #submit-payment:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="checkout-page">
    <!-- Left: Billing and Stripe Form -->
    <div class="checkout-details">
        <h2>Billing Information</h2>
        <form id="billing-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="card-element">Credit or Debit Card</label>
            <div id="card-element"></div>

            <div id="error-message" style="color: red; margin-top: 10px;"></div>

            <button type="submit" id="submit-payment">Pay Now</button>
        </form>
    </div>

    <!-- Right: Product Summary -->
    <div class="product-summary">
        <h3>Product Details</h3>
        <p><strong>Product Name:</strong> <span id="product-name"></span></p>
        <p><strong>Subscription Plan:</strong> <span id="subscription-plan"></span></p>
        <p><strong>Total Price for First Month:</strong> $<span id="total-price"></span></p>

        <div class="notice-box">
            <strong>Notice:</strong><br>
            This is an automatic subscription system. You will be charged automatically each month after the first payment based on your selected subscription plan.
        </div>
    </div>
</div>

<script>
    // Parse query parameters
    const params = new URLSearchParams(window.location.search);
    const product = params.get('product');
    const price = params.get('price');
    const subscription = params.get('subscription');

    // Populate values
    document.getElementById('product-name').textContent = product;
    document.getElementById('subscription-plan').textContent = subscription + ' months';
    document.getElementById('total-price').textContent = price;

    // Stripe Integration
    const stripe = Stripe('pk_test_51NHtioI7D8hVACYMGNJNInarAq3XvUTkGQh6fABjcisQMDKE3eA6myuCZK4WAmz2tAfu3m3vG8lZAYvR6GbpV20600uHHIDjAh');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    // Form Submission
    const form = document.getElementById('billing-form');
    const errorDisplay = document.getElementById('error-message');

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const { token, error } = await stripe.createToken(card);

        if (error) {
            errorDisplay.textContent = error.message;
        } else {
            // Send token + form data to server
            const formData = {
                name: form.name.value,
                email: form.email.value,
                address: form.address.value,
                token: token.id,
                product,
                price,
                subscription
            };

            fetch('/checkout/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Payment successful!');
                    window.location.href = '/thank-you';
                } else {
                    errorDisplay.textContent = data.message || 'Something went wrong.';
                }
            });
        }
    });
</script>

</body>
</html>
