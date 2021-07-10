<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdsBoostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_boosters', function (Blueprint $table) {

            $table->dropForeign(['ads_type_id']);
            $table->dropIndex(['ads_type_id']);
            $table->dropColumn('ads_type_id');

            $table->unsignedBigInteger('product_attribute_id')->index()->after('id');

            $table->foreign('product_attribute_id')->references('id')->on('product_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
