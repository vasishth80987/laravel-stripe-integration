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

    Route::get('subscription-packages/subscriptions', config('stripe_integration.package_api_controller').'@showSubscriptions')->name('subscription-packages.subscriptions');
    Route::delete('subscription-packages/destroy', config('stripe_integration.package_api_controller').'@massDestroy')->name('subscription-packages.massDestroy');
    Route::resource('subscription-packages', config('stripe_integration.package_api_controller'));

});
