<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 3/05/20
 * Time: 2:37 AM
 */
namespace Vsynch\StripeIntegration\Console;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Stripe\Plan;
use Stripe\Price;
use Stripe\Product;
use Vsynch\StripeIntegration\SubscriptionPackage;

class ImportStripeProducts extends Command
{
    protected $signature = 'vsynch:stripe-integration-import';
    protected $description = 'Import and populate database with products from Stripe Account';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $products = Product::all();
        $imports = [];
        try{
            foreach($products->data as $product){
                $plans = Plan::all(['product'=>$product->id]);
                $prices = Price::all(['product'=>$product->id]);

                foreach($prices->data as $plan) {

                    $record = SubscriptionPackage::where(['stripe_pricing_plan' => $plan->id])->first();
                    if (!$record) {
                        $record = new SubscriptionPackage();
                    }

                    $imports[] = $product->name;
                    $record->name = $product->name;
                    $record->stripe_product = $product->id;
                    $record->stripe_pricing_plan = $plan->id;
                    $record->plan_name = $plan->nickname;
                    $record->price = (float)($plan->unit_amount/100);
                    $record->pricing_interval = $plan->type=='recurring'?$plan->recurring->interval:null;
                    $record->pricing_interval_count = $plan->type=='recurring'?$plan->recurring->interval_count:null;
                    $record->pricing_billing_scheme = $plan->billing_scheme;
                    $record->status = $plan->active;

                    $record->save();

                }
            }
            $this->info('Import completed!'.count($imports).' products/plans have been imported to database.');
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }
}