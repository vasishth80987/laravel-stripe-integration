<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 24/02/20
 * Time: 4:39 PM
 */

namespace Vsynch\StripeIntegration\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Laravel\Cashier\Subscription;
use phpDocumentor\Reflection\Types\Parent_;
use Stripe\Event;
use Vsynch\StripeIntegration\Events\CustomerSubscriptionCreated;
use Vsynch\StripeIntegration\Events\CustomerSubscriptionUpdated;
use Vsynch\StripeIntegration\Events\CustomerSubscriptionDeleted;
use Vsynch\StripeIntegration\Events\CustomerCreated;
use Vsynch\StripeIntegration\Events\CustomerUpdate;
use Vsynch\StripeIntegration\Events\CustomerDeleted;


class StripeController extends WebhookController
{


    public function handleCustomerSubscriptionUpdated( $payload)
    {
        $response = parent::handleCustomerSubscriptionUpdated($payload);

        if($response->getStatusCode()==200) {
            $event = Event::constructFrom($payload);
            $user = $this->getUserByStripeId($event->customer);
            $user->subscriptions->filter(function (Subscription $subscription) use ($event) {
                return $subscription->stripe_id === $event->id;
            });
            dd($event);
            event(new CustomerSubscriptionUpdated());
            return $response;
        }
        else {
            http_response_code(400);
            exit();
        }
    }

    public function handleCustomerSubscriptionDeleted(array $payload)
    {
        $response = parent::handleCustomerSubscriptionDeleted($payload);

        if($response->getStatusCode()==200) {
            $event = Event::constructFrom($payload);
            $user = $this->getUserByStripeId($event->customer);
            $user->subscriptions->filter(function (Subscription $subscription) use ($event) {
                return $subscription->stripe_id === $event->id;
            });
            event(new CustomerSubscriptionUpdated());
            return $response;
        }else {
            http_response_code(400);
            exit();
        }
    }

    public function handleCustomerDeleted(array $payload)
    {
        $response = parent::handleCustomerDeleted($payload);

        if($response->getStatusCode()==200) {
            $event = Event::constructFrom($payload);
            $user = $this->getUserByStripeId($event->customer);
            $user->subscriptions->filter(function (Subscription $subscription) use ($event) {
                return $subscription->stripe_id === $event->id;
            });
            event(new CustomerSubscriptionUpdated());
            return $response;
        }else {
            http_response_code(400);
            exit();
        }
    }

    public function handleCustomerUpdated(array $payload)
    {
        $response = parent::handleCustomerUpdated($payload);

        if($response->getStatusCode()==200) {
            $event = Event::constructFrom($payload);
            $user = $this->getUserByStripeId($event->customer);
            $user->subscriptions->filter(function (Subscription $subscription) use ($event) {
                return $subscription->stripe_id === $event->id;
            });
            event(new CustomerSubscriptionUpdated());
            return $response;
        }else {
            http_response_code(400);
            exit();
        }
    }

    public function handleCustomerSubscriptionCreated( $payload)
    {
        try {
            $event = Event::constructFrom($payload);
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }
        Log::info(json_encode($event));
        return $this->successMethod();
    }

    protected function handleInvoicePaymentActionRequired(array $payload)
    {
        $response = parent::handleInvoicePaymentActionRequired($payload);
        if($response->getStatusCode()==200) {
            $event = Event::constructFrom($payload);
            $user = $this->getUserByStripeId($event->customer);
            $user->subscriptions->filter(function (Subscription $subscription) use ($event) {
                return $subscription->stripe_id === $event->id;
            });
            event(new CustomerSubscriptionUpdated());
            return $response;
        }else {
            http_response_code(400);
            exit();
        }
    }

    public function handleInvoicePaymentSucceeded( $payload)
    {
        try {
            $event = Event::constructFrom($payload);

        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }
        Log::info(json_encode($event));
        return $this->successMethod();
    }
}