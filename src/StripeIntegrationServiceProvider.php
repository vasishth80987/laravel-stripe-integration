<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 2/05/20
 * Time: 9:36 AM
 */
namespace Vsynch\StripeIntegration;

use Illuminate\Support\ServiceProvider;
use Vsynch\StripeIntegration\Console\ImportStripeProducts;

class StripeIntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        $this->mergeConfigFrom(__DIR__.'/Config/stripe_integration.php', 'stripe_integration');

        $this->app->bind('subscriptionPackage', function ($app) {
            return new SubscriptionPackage();
        });

        $this->app->register(StripeIntegrationEventServiceProvider::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Config/stripe_integration.php' => config_path('stripe_integration.php')
            ], 'vsynch-stripe-integration-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'vsynch-stripe-integration-migrations');

            $this->publishes([
                __DIR__.'/Resources/lang' => resource_path('lang/vendor/StripeIntegration'),
            ], 'vsynch-stripe-integration-lang');
            $this->publishes([
                __DIR__.'/Resources/views/' => resource_path('views/vendor/vsynch/stripe-integration'),
            ], 'vsynch-stripe-integration-views');
        }

        $this->loadTranslationsFrom(__DIR__.'/Resources/lang', 'StripeIntegration');

        $this->loadViewsFrom(__DIR__.'/Resources/views', 'StripeIntegration');

        $this->commands([
            ImportStripeProducts::class]);
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }
}