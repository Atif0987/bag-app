Laravel Shopify Subscription Bag Rental Service

This is a Laravel-based project for a Shopify Subscription Bag Rental Service. The app allows users to subscribe to a bag rental service with different subscription plans (3, 6, and 9 months), with an option to swap bags each month for an additional fee. The app integrates with the Shopify API, Stripe, and handles subscription management, payment processing, and user notifications.

Features

Subscription Plans: Users can choose from 3, 6, or 9-month subscription plans with auto-renew and cancellation options.

Bag Swapping: A feature allowing users to swap their rented bag each month for a $20 swapping fee.

Bag Availability Management: Tracks the availability of bags and maintains a waitlist for unavailable bags.

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

Blade Templates: For rendering dynamic content on the frontend and handling user interactions.

HTML/CSS: For structuring and styling the pages.

JavaScript: For frontend functionality like payment processing and handling interactions with Stripe.

Stripe.js: For integrating Stripe’s frontend features (such as the payment form and card elements) into the app.

Others:

Git: Version control for managing code changes.

Composer: PHP dependency manager for managing libraries and packages.