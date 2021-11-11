<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->longText('about')->nullable()->after('status');
            $table->longText('aboutservice')->nullable()->after('about');
            $table->longText('team')->nullable()->after('aboutservice');
            $table->longText('other')->nullable()->after('team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('about');
            $table->dropColumn('aboutservice');
            $table->dropColumn('team');
            $table->dropColumn('other');
        });
    }
}
