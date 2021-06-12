<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorIntoAdsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ads_types')) {
            Schema::table('ads_types', function (Blueprint $table) {

                $table->string('color')->after('description');
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
        if (Schema::hasTable('ads_types')) {
            Schema::table('ads_types', function (Blueprint $table) {
                //
            });
        }
    }
}
