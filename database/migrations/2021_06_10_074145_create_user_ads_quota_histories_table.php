<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdsQuotaHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_ads_quota_histories')) {
            return;
        }

        Schema::create('user_ads_quota_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_ads_quota_id')->index();
            $table->unsignedBigInteger('initial_quantity')->default(0);
            $table->bigInteger('process_quantity')->default(0);
            $table->unsignedBigInteger('remaining_quantity')->default(0);
            $table->morphs('sourceable');
            $table->morphs('applicable');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_ads_quota_id')->references('id')->on('user_ads_quotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ads_quota_histories');
    }
}
