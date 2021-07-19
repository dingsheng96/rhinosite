<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropServicesFromProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->dropColumn('services');
            $table->dropColumn('slug');
            $table->dropColumn('published');

            $table->enum('status', ['published', 'draft'])->default('published')->after('unit_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->longText('services')->after('user_id');
            $table->string('slug')->after('unit_value');
            $table->boolean('published')->after('unit_value');

            $table->dropColumn('status');
        });
    }
}
