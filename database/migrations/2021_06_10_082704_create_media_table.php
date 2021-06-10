<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('media')) {
            return;
        }

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('sourceable');
            $table->string('type')->index();
            $table->string('original_filename')->nullable();
            $table->string('filename')->nullable();
            $table->string('path')->nullable();
            $table->string('extension')->nullable();
            $table->double('size', 30, 8)->default(0);
            $table->string('mime')->nullable();
            $table->longText('properties')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
