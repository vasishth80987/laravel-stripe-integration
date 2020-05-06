<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 3/05/20
 * Time: 4:10 AM
 */
namespace Vsynch\StripeIntegration\Traits;

use Laravel\Cashier\Billable;

trait StripeBillable{
    use Billable;

    public function getActiveSubscriptions(){
        return $this->subscriptions()->where('stripe_status','!=','canceled');
    }
}