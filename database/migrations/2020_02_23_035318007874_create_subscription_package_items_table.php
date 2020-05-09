<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPackageItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('subscription_package_items')) {
            Schema::create('subscription_package_items', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('subscription_package_id');
                $table->string('stripe_subscribable_item_id');
                $table->string('stripe_subscribable_item_type');
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
        Schema::dropIfExists('subscription_package_items');
    }
}
