<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {

                // drop foreign keys
                $table->dropForeign(['registration_id']);

                // drop index
                $table->dropIndex(['registration_id']);
                $table->dropIndex(['reg_no']);
                $table->dropIndex(['mobile_no']);

                // drop column
                $table->dropColumn('registration_id');
                $table->dropColumn('reg_no');
                $table->dropColumn('tel_no');
                $table->dropColumn('mobile_no');

                // add column
                $table->string('phone')->index()->nullable()->after('name');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
