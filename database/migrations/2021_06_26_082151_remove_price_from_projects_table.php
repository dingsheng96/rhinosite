<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePriceFromProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropIndex(['currency_id']);
            $table->dropColumn('currency_id');
            $table->dropColumn('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('currency_id')->index()->after('materials');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }
}
