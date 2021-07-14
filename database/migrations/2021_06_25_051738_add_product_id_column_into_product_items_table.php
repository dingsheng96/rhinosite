<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductIdColumnIntoProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('product_items')) {

            Schema::table('product_items', function (Blueprint $table) {

                $table->unsignedBigInteger('product_id')->index()->after('id');

                $table->foreign('product_id')->references('id')->on('products');
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
        if (Schema::hasTable('product_items')) {

            Schema::table('product_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
                $table->dropIndex(['product_id']);
                $table->dropColumn('product_id');
            });
        }
    }
}
