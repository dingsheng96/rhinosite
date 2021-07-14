<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantityIntoProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('product_attributes')) {
            Schema::table('product_attributes', function (Blueprint $table) {
                $table->bigInteger('quantity')->after('sku');
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
        if (Schema::hasTable('product_attributes')) {
            Schema::table('product_attributes', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
}
