<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('projects')) {
            return;
        }

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->longText('services')->nullable();
            $table->longText('materials')->nullable();
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('unit_id')->index();
            $table->unsignedBigInteger('unit_value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
