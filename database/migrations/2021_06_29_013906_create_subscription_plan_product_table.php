<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionPlanProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plan_product', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_plan_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('quantity');

            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plan_product');
    }
}
