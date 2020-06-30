<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 27/06/20
 * Time: 1:17 PM
 */

Route::group(['prefix' => config('stripe_integration.api_route_url_prefix'), 'as' => config('stripe_integration.api_route_name_prefix'),
    'middleware' => config('stripe_integration.api_route_middleware')], function () {

    Route::post('stripe/update-payment-method', config('stripe_integration.package_api_controller').'@updateUserPaymentMethod')->name('stripe.update-payment-method');
    Route::get('stripe/edit-payment-method', config('stripe_integration.package_api_controller').'@editUserPaymentMethod')->name('stripe.edit-payment-method');

    Route::get('subscription-packages/{subscription_package}/subscribe', config('stripe_integration.package_api_controller').'@subscribe')->name('subscription-packages.subscribe');
    Route::get('subscription-packages/subscriptions', config('stripe_integration.package_api_controller').'@showSubscriptions')->name('subscription-packages.subscriptions');
    Route::get('subscription-packages/{subscription_package}/change-plan', config('stripe_integration.package_api_controller').'@changePlan')->name('subscription-packages.change-plan');
    Route::get('subscription-packages/{subscription_package}/decrement-quanity', config('stripe_integration.package_api_controller').'@decrementSubscriptionQuantity')->name('subscription-packages.decrement-quantity');
    Route::get('subscription-packages/{subscription_package}/unsubscribe', config('stripe_integration.package_api_controller').'@unsubscribe')->name('subscription-packages.unsubscribe');
    Route::get('subscription-packages/{subscription_package}/unsubscribe-now', config('stripe_integration.package_api_controller').'@unsubscribeNow')->name('subscription-packages.unsubscribe-now');
    Route::get('subscription-packages/{subscription_package}/resume-subscription', config('stripe_integration.package_api_controller').'@resumeSubscription')->name('subscription-packages.resume-subscription');
    Route::delete('subscription-packages/destroy', config('stripe_integration.package_api_controller').'@massDestroy')->name('subscription-packages.massDestroy');
    Route::resource('subscription-packages', config('stripe_integration.package_api_controller'));

});
