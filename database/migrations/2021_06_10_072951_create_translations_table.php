<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('translations')) {
            return;
        }

        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('sourceable');
            $table->unsignedBigInteger('language_id')->index();
            $table->longText('value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
}
