<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSlotsColumnsFromProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropColumn('slot');
            $table->dropColumn('slot_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_attributes', function (Blueprint $table) {

            $table->unsignedBigInteger('slot')->nullable()->after('validity');
            $table->enum('slot_type', ['daily', 'weekly', 'monthly'])->nullable()->after('validity');
        });
    }
}
