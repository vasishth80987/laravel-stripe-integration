<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 9/05/20
 * Time: 7:26 AM
 */
namespace Vsynch\StripeIntegration\Listeners;

use Vsynch\StripeIntegration\Events\CustomerSubscriptionUpdated;

class HandleCustomerSubscriptionUpdated
{
    public function handle(CustomerSubscriptionUpdated $event)
    {
        //$event->user,$event->stripe_event;
        Log::info('Mail sent to '.$event->user->name);
        Mail::to($event->user)->send(new StripeSubscriptionUpdated($event->user,$event->stripe_event));
    }
}