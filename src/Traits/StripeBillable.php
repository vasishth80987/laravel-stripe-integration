<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 3/05/20
 * Time: 4:10 AM
 */
namespace Vsynch\StripeIntegration\Traits;

use Carbon\Carbon;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Stripe\InvoiceItem as StripeInvoiceItem;

trait StripeBillable{
    use Billable;

    /**
     * Get all of the subscriptions for the Stripe model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->join('subscription_packages', 'subscriptions.stripe_plan', '=', 'subscription_packages.stripe_pricing_plan')->select('subscriptions.*','subscription_packages.id as package_id','subscription_packages.name as package_name','subscription_packages.display_name as display_name','subscription_packages.plan_name as plan_nickname','subscription_packages.pricing_interval as pricing_interval')->orderBy('subscriptions.created_at', 'desc');
    }

    public function getActiveSubscriptions(){
        return $this->subscriptions()->where('stripe_status','==','active')->orWhere('ends_at', '>=', Carbon::now());
    }

    public function invoiceFor($description, $amount = null, array $tabOptions = [], array $invoiceOptions = [])
    {
        $this->tab($description, $amount, $tabOptions);

        return $this->invoice($invoiceOptions);
    }

    public function tab($description, $amount, array $options = [])
    {
        $this->assertCustomerExists();

        if(!is_null($amount))
        $options = array_merge([
            'customer' => $this->stripe_id,
            'amount' => $amount,
            'currency' => $this->preferredCurrency(),
            'description' => $description,
        ], $options);
        else
            $options = array_merge([
                'customer' => $this->stripe_id,
                'currency' => $this->preferredCurrency(),
                'description' => $description,
            ], $options);
        return StripeInvoiceItem::create($options, $this->stripeOptions());
    }
}