<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIntoUsersTable extends Migration
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
                $table->softDeletes();
                $table->unsignedBigInteger('registration_id')->index()->after('remember_token');
                $table->enum('status', ['active', 'inactive'])->index()->after('remember_token');
                $table->string('reg_no')->nullable()->index()->after('email')->comment('company reg no');
                $table->string('tel_no')->nullable()->after('email');
                $table->string('mobile_no')->index()->after('email');

                $table->foreign('registration_id')->references('id')->on('registrations');
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
        //
    }
}
