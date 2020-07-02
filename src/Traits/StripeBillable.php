<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 3/05/20
 * Time: 4:10 AM
 */
namespace Vsynch\StripeIntegration\Traits;

use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

trait StripeBillable{
    use Billable;

    /**
     * Get all of the subscriptions for the Stripe model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->join('subscription_packages', 'subscriptions.stripe_plan', '=', 'subscription_packages.stripe_pricing_plan')->select('subscriptions.*','subscription_packages.id as package_id','subscription_packages.name as package_name','subscription_packages.display_name as display_name','subscription_packages.plan_name as plan_nickname')->orderBy('subscriptions.created_at', 'desc');
    }

    public function getActiveSubscriptions(){
        return $this->subscriptions()->where('stripe_status','!=','canceled');
    }
}