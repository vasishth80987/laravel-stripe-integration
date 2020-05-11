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
                foreach($plans->data as $plan) {
                    $record = SubscriptionPackage::where(['stripe_pricing_plan' => $plan->id])->first();
                    if (!$record) {
                        $imports[] = $product->name;
                        SubscriptionPackage::create(['name' => $product->name, 'stripe_product' => $product->id, 'stripe_pricing_plan' => $plan->id,'plan_name' => $plan->nickname]);
                    }
                    else{
                        $imports[] = $product->name;
                        $record->name = $product->name;
                        $record->stripe_product = $product->id;
                        $record->stripe_pricing_plan = $plan->id;
                        $record->plan_name = $plan->nickname;

                        $record->save();
                    }

                }
            }
            $this->info('Import completed! '.count($imports).' products/plans have been imported to database.');
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }
}