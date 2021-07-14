<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventoryIntoPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('description');
            $table->unsignedBigInteger('quantity')->default(0)->after('description');
            $table->enum('stock_type', ['infinite', 'finite'])->default('finite')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('stock_type');
            $table->dropColumn('status');
        });
    }
}
