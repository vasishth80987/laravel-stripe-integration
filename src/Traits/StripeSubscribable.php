<?php

namespace Vsynch\StripeIntegration\Traits;


trait StripeSubscribable
{
    /**
     * Upload a single file in the server
     * and return the random (string) filename if successful and (boolean) false if not
     *
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @return false|string
     */
    public function subscriptionPackages()
    {
        return $this->morphToMany('Vsynch\StripeIntegration\SubscriptionPackage', 'stripe_subscribable_item','subscription_package_items')->withPivot('quantity');
    }
}
