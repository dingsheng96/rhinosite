<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductAttributeIdInUserAdsQuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_ads_quotas', function (Blueprint $table) {
            $table->dropForeign(['product_attribute_id']);
            $table->dropIndex(['product_attribute_id']);

            $table->renameColumn('product_attribute_id', 'product_id');
            $table->index(['product_id']);
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
        Schema::table('user_ads_quotas', function (Blueprint $table) {

            $table->dropForeign(['product_id']);
            $table->dropIndex(['product_id']);

            $table->renameColumn('product_id', 'product_attribute_id');
            $table->index(['product_attribute_id']);
            $table->foreign('product_attribute_id')->references('id')->on('product_attributes');
        });
    }
}
