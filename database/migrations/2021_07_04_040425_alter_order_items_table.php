<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->morphs('orderable');

            $table->dropForeign(['product_id']);
            $table->dropIndex(['product_id']);
            $table->dropColumn('product_id');

            $table->dropForeign(['product_type_id']);
            $table->dropIndex(['product_type_id']);
            $table->dropColumn('product_type_id');
        });

        DB::statement("ALTER TABLE order_items MODIFY COLUMN orderable_id BIGINT(20) UNSIGNED NOT NULL AFTER order_id");
        DB::statement("ALTER TABLE order_items MODIFY COLUMN orderable_type VARCHAR(191) NOT NULL AFTER order_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {

            $table->dropColumn('orderable_type');
            $table->dropColumn('orderable_id');
        });
    }
}
