<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 2/05/20
 * Time: 9:38 AM
 */
return [
    'stripe_controller' => 'Vsynch\StripeIntegration\Controllers\StripeController',
    'package_controller' => 'Vsynch\StripeIntegration\Controllers\SubscriptionPackagesController',
    'package_class' => 'Vsynch\StripeIntegration\SubscriptionPackage',
    'package_request_namespace' => 'Vsynch\StripeIntegration\Requests',
    'web_route_middleware' => ['web','auth','verified'],
    'web_route_url_prefix' => 'admin',
    'web_route_name_prefix' => 'admin.',
    'enable_event_listeners' => false,
    'subscription_package_item_classes' => ['product'=>'App\Product'],
    'event_listeners' => [
        \Vsynch\StripeIntegration\Events\CustomerUpdated::class => [
            \Vsynch\StripeIntegration\Listeners\HandleCustomerUpdated::class,
        ],
        \Vsynch\StripeIntegration\Events\CustomerDeleted::class => [
            \Vsynch\StripeIntegration\Listeners\HandleCustomerDeleted::class,
        ],
        \Vsynch\StripeIntegration\Events\CustomerCreated::class => [
            \Vsynch\StripeIntegration\Listeners\HandleCustomerCreated::class,
        ],
        \Vsynch\StripeIntegration\Events\CustomerSubscriptionCreated::class => [
            \Vsynch\StripeIntegration\Listeners\HandleCustomerSubscriptionCreated::class,
        ],
        \Vsynch\StripeIntegration\Events\CustomerSubscriptionUpdated::class => [
            \Vsynch\StripeIntegration\Listeners\HandleCustomerSubscriptionUpdated::class,
        ],
        \Vsynch\StripeIntegration\Events\CustomerSubscriptionDeleted::class => [
            \Vsynch\StripeIntegration\Listeners\HandleCustomerSubscriptionDeleted::class,
        ],
        \Vsynch\StripeIntegration\Events\InvoicePaymentActionRequired::class => [
            \Vsynch\StripeIntegration\Listeners\HandleInvoicePaymentActionRequired::class,
        ],
        \Vsynch\StripeIntegration\Events\InvoicePaymentSucceeded::class => [
            \Vsynch\StripeIntegration\Listeners\HandleInvoicePaymentSucceeded::class,
        ],
    ]
];