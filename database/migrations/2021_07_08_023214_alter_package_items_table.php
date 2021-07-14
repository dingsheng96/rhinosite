<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_items', function (Blueprint $table) {

            $table->dropColumn('packageable_type');
            $table->renameColumn('packageable_id', 'product_attribute_id');

            $table->index('product_attribute_id');
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
        Schema::table('package_items', function (Blueprint $table) {

            $table->string('packageable_type')->index()->after('package_id');
            $table->renameColumn('product_attribute_id', 'packageable_id');

            $table->dropForeign(['product_attribute_id']);
            $table->dropIndex(['product_attribute_id']);
        });
    }
}
