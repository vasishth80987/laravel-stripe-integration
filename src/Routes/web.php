<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 2/05/20
 * Time: 10:58 AM
 */


//Stripe web hooks
Route::post(
    'stripe/webhook',
    '\Vsynch\StripeIntegration\Controllers\StripeController@handleWebhook'
);
Route::group(['prefix' => config('stripe_integration.web_route_url_prefix'), 'as' => config('stripe_integration.web_route_name_prefix'),
    'middleware' => config('stripe_integration.web_route_middleware')], function () {

    Route::post('stripe/update-payment-method', config('stripe_integration.package_controller').'@updateUserPaymentMethod')->name('stripe.update-payment-method');
    Route::get('stripe/edit-payment-method', config('stripe_integration.package_controller').'@editUserPaymentMethod')->name('stripe.edit-payment-method');

    Route::get('subscription-packages/{subscription_package}/subscribe', config('stripe_integration.package_controller').'@subscribe')->name('subscription-packages.subscribe');
    Route::get('subscription-packages/{subscription_package}/subscriptions', config('stripe_integration.package_controller').'@showSubscriptions')->name('subscription-packages.subscriptions');
    Route::get('subscription-packages/{subscription_package}/change-plan', config('stripe_integration.package_controller').'@changePlan')->name('subscription-packages.change-plan');
    Route::get('subscription-packages/{subscription_package}/unsubscribe', config('stripe_integration.package_controller').'@unsubscribe')->name('subscription-packages.unsubscribe');
    Route::get('subscription-packages/{subscription_package}/unsubscribe-now', config('stripe_integration.package_controller').'@unsubscribeNow')->name('subscription-packages.unsubscribe-now');
    Route::get('subscription-packages/{subscription_package}/resume-subscription', config('stripe_integration.package_controller').'@resumeSubscription')->name('subscription-packages.resume-subscription');
    Route::delete('subscription-packages/destroy', config('stripe_integration.package_controller').'@massDestroy')->name('subscription-packages.massDestroy');
    Route::resource('subscription-packages', config('stripe_integration.package_controller'));

});