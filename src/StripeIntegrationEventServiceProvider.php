<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 9/05/20
 * Time: 7:22 AM
 */
namespace Vsynch\StripeIntegration;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class StripeIntegrationEventServiceProvider extends ServiceProvider
{

    protected $listen = [[]];

    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        parent::__construct($app);
        $this->listen = config('stripe_integration.event_listeners');
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}