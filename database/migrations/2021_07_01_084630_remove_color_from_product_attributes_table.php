<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColorFromProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->dropColumn('is_available');

            $table->enum('status', ['active', 'inactive'])->default('active')->after('quantity');
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
            $table->string('color')->nullable()->after('quantity');
            $table->boolean('is_available')->after('quantity');

            $table->dropColumn('status');
        });
    }
}
