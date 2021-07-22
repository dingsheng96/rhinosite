<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSourceableColumnInRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ratings', function (Blueprint $table) {

            // $table->dropIndex(['sourceable_type', 'sourceable_id']);

            $table->renameColumn('sourceable_type', 'rateable_type');
            $table->renameColumn('sourceable_id', 'rateable_id');

            $table->index(['rateable_type', 'rateable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {

            $table->dropIndex(['sourceable_type', 'sourceable_id']);

            $table->renameColumn('rateable_type', 'sourceable_type');
            $table->renameColumn('rateable_id', 'sourceable_id');

            $table->index(['sourceable_type', 'sourceable_id']);
        });
    }
}
