<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegNoIntoUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {

            $table->string('reg_no')->index()->after('user_id');
            $table->renameColumn('industry_since', 'business_since');
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

            $table->dropIndex(['reg_no']);
            $table->dropColumn('reg_no');

            $table->renameColumn('business_since', 'industry_since');
        });
    }
}
