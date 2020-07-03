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
    protected $fillable = ['name', 'stripe_product', 'stripe_pricing_plan', 'plan_name', 'display_name'];

    public function items($type)
    {
        return $this->morphedByMany($type, 'stripe_subscribable_item','subscription_package_items')->withPivot('quantity');
    }

    public function checkIfIncludes($type,$id){

        if(!is_array($id)) $id = [$id];
        $items = $this->items($type)->get();

        foreach($items as $item){
            if(in_array($item->id,$id)) return true;
        }

        return false;
    }
}
