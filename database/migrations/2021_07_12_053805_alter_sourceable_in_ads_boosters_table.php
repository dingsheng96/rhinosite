<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSourceableInAdsBoostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_boosters', function (Blueprint $table) {

            $table->dropIndex(['sourceable_type', 'sourceable_id']);

            $table->renameColumn('sourceable_type', 'boostable_type');
            $table->renameColumn('sourceable_id', 'boostable_id');

            $table->index(['boostable_type', 'boostable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads_boosters', function (Blueprint $table) {
            $table->dropIndex(['boostable_type', 'boostable_id']);

            $table->renameColumn('boostable_type', 'sourceable_type');
            $table->renameColumn('boostable_id', 'sourceable_id');

            $table->index(['sourceable_type', 'sourceable_id']);
        });
    }
}
