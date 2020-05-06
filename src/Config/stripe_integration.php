<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 2/05/20
 * Time: 9:38 AM
 */
return [
    'stripe_controller' => 'StripeController',
    'package_controller' => 'SubscriptionPackagesController',
    'package_class' => 'Vsynch\StripeIntegration\SubscriptionPackage',
    'package_request_namespace' => 'Vsynch\StripeIntegration\Requests',
    'web_route_middleware' => ['web','auth','verified'],
    'web_route_url_prefix' => 'admin',
    'web_route_name_prefix' => 'admin.',
    'web_route_controller_namespace' => 'Vsynch\StripeIntegration\Controllers',
];