<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 9/05/20
 * Time: 7:26 AM
 */
namespace Vsynch\StripeIntegration\Listeners;

use Vsynch\StripeIntegration\Events\CustomerSubscriptionCreated;

class HandleCustomerSubscriptionCreated
{
    public function handle(CustomerSubscriptionCreated $event)
    {
        //$event->user,$event->stripe_event;

    }
}