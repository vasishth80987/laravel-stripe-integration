<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('subscription_packages')) {
            Schema::create('subscription_packages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('plan_name')->nullable();
                $table->string('stripe_product');
                $table->string('stripe_pricing_plan');
                $table->float('price')->default(0.00);
                $table->longText('description')->nullable();
                $table->longText('short_description')->nullable();
                $table->longText('meta')->nullable();
                $table->string('pricing_interval')->nullable();
                $table->integer('pricing_interval_count')->nullable();
                $table->string('pricing_billing_scheme')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_packages');
    }
}
