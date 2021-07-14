<?php

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameProductTypeIdInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_type_id']);
            $table->dropIndex(['product_type_id']);
            $table->dropColumn('product_type_id');

            $table->unsignedBigInteger('product_category_id')->index()->after('description');
            $table->foreign('product_category_id')->references('id')->on(app(ProductCategory::class)->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
            $table->dropIndex(['product_category_id']);
            $table->dropColumn('product_category_id');

            $table->unsignedBigInteger('product_type_id')->index()->after('description');
            $table->foreign('product_type_id')->references('id')->on(app(ProductCategory::class)->getTable());
        });
    }
}
