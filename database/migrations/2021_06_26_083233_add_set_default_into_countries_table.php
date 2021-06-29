<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSetDefaultIntoCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('countries')) {

            Schema::table('countries', function (Blueprint $table) {
                $table->boolean('set_default')->default(false)->after('name');
                $table->string('code')->nullable()->after('name');
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
        if (Schema::hasTable('countries')) {

            Schema::table('countries', function (Blueprint $table) {
                $table->dropColumn('set_default');
                $table->dropColumn('code');
            });
        }
    }
}
