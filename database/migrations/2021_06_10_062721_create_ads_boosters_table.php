<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsBoostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ads_boosters')) {
            return;
        }

        Schema::create('ads_boosters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ads_type_id')->index();
            $table->morphs('sourceable');
            $table->timestamp('boosted_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ads_type_id')->references('id')->on('ads_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_boosters');
    }
}
