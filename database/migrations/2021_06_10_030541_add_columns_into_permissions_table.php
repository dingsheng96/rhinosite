<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->softDeletes();
                $table->enum('action', ['create', 'update', 'read', 'delete'])->after('guard_name');
                $table->unsignedBigInteger('module_id')->index()->after('guard_name');
                $table->longText('description')->nullable()->after('guard_name');
                $table->string('display')->after('guard_name');

                $table->foreign('module_id')->references('id')->on('modules');
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
