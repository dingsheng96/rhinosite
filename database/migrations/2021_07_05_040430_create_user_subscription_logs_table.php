<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubscriptionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_subscription_id')->index();
            $table->timestamp('renewed_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_subscription_id')->references('id')->on('user_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscription_logs');
    }
}
