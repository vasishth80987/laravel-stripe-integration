<?php

namespace Vsynch\StripeIntegration;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscription_packages';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'stripe_product', 'stripe_pricing_plan'];
}
