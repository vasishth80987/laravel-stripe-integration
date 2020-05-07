# Laravel Stripe Integration
A Laravel package that provides customisable product packages for stripe integration using cashier.

## Installation
```
composer require vsynch/laravel-stripe-integration

```

Publish package files
```
php artisan vendor:publish --provider="Vsynch\StripeIntegration\StripeIntegrationServiceProvider"

```

Make a stripe account and enter the following details in your application's .env file
```
STRIPE_KEY={your-stripe-key}
STRIPE_SECRET={your-stripe-secret}
STRIPE_WEBHOOK_SECRET={your-stripe-webhook-secret}
CASHIER_CURRENCY=aud
```

Run migrations and seed database
```
php artisan migrate
```

Run Package Setup Command: Imports all products from Stripe Account
```
php artisan vsynch:stripe-integration-import
```

##Usage
Add the StripeBillable trait to user model
```
use StripeBillable;
```
After Publishing the packages files, the controllers and mails can later be customised via the config file

