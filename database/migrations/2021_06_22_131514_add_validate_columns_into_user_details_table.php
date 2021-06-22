<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidateColumnsIntoUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_details')) {

            Schema::table('user_details', function (Blueprint $table) {

                $table->timestamp('validated_at')->nullable()->after('pic_email');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('pic_email');
                $table->unsignedBigInteger('validated_by')->index()->after('pic_email');

                $table->foreign('validated_by')->references('id')->on('users');
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
        if (Schema::hasTable('user_details')) {

            Schema::table('user_details', function (Blueprint $table) {

                $table->dropForeign(['validated_by']);
                $table->dropIndex(['validated_by']);

                $table->dropColumn('validated_at');
                $table->dropColumn('validated_by');
                $table->dropColumn('status');
            });
        }
    }
}
