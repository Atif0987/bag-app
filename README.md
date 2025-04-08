Laravel Shopify Subscription Bag Rental Service

This is a Shopify Subscription Bag Rental Non-Embeded App using Laravel. I developed the Non-embedded app because of the requirements and technical considerations. If I made this Embedded app, I would face Shopify limitations and restrictions like swap functionality, login user data, user dashboard restrictions, etc.  The app allows users to subscribe to a bag rental service with different subscription plans (3, 6, and 9 months), with an option to swap bags each month for an additional fee. The app integrates with the Shopify API and Stripe and handles subscription management, payment processing, and user dashboard.

Important Note: As I mentioned above, this is a Non-Embedded app so I am unable to provide the direct preview link because local host and Ngrok  for tunneling. So, for a demo, I made a loom video (https://www.loom.com/share/5c95d9553f1f4d2fa330649b4cb23b73)  and other complete details here. However, I give the invitations to the provided emails for the Shopify store. 

Features

Subscription Plans: Users can choose from 3, 6, or 9-month subscription plans with auto-renew and cancellation options.

Bag Swapping: A feature allowing users to swap their rented bag each month for a $20 swapping fee.

User Dashboard: On the dashboard, every user sees the details of their order.

User Profile: Users can manage their subscriptions, rental history, and swap history.

Stripe Integration: Handles payment and subscription via Stripe with automatic billing and secure payment processing.

Shopify Integration: The app seamlessly integrates with the Shopify API to manage products, handle orders, and process payments.



Tech Stack

Backend:
Laravel: PHP framework for building the backend API and handling business logic.

Shopify API: For managing products, customers, and orders in Shopify.

Stripe API: For processing subscription payments, managing payment methods, and handling recurring billing.

MySQL: Database to store subscription data, user profiles, rental history, and payment transactions.

Frontend:

Blade Templates: For rendering dynamic content on the front end and handling user interactions.

HTML/CSS: For structuring and styling the pages.

JavaScript: For frontend functionality like payment processing and handling interactions with Stripe.

Stripe.js: For integrating Stripe’s frontend features (such as the payment form and card elements) into the app.

Others:

Git: Version control for managing code changes.

Composer: PHP dependency manager for managing libraries and packages.