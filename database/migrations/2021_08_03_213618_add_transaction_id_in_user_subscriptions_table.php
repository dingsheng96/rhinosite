<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionIdInUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->timestamp('terminated_at')->nullable()->after('next_billing_at');
            $table->unsignedBigInteger('transaction_id')->index()->nullable()->after('status');
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {

            $table->dropForeign(['transaction_id']);
            $table->dropIndex(['transaction_id']);
            $table->dropColumn('transaction_id');

            $table->dropColumn('terminated_at');
        });
    }
}
