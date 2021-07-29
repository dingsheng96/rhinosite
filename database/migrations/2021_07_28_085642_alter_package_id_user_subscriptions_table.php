<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackageIdUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {

            $table->dropForeign(['package_id']);
            $table->dropIndex(['package_id']);
            $table->dropColumn('package_id');

            $table->morphs('subscribable');
            $table->timestamp('next_billing_at')->nullable()->after('auto_billing');
        });

        DB::statement("ALTER TABLE user_subscriptions MODIFY COLUMN subscribable_id BIGINT(20) UNSIGNED NOT NULL AFTER user_id");
        DB::statement("ALTER TABLE user_subscriptions MODIFY COLUMN subscribable_type VARCHAR(255) NOT NULL AFTER user_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {

            $table->unsignedBigInteger('package_id')->index()->after('user_id');
            $table->foreign('package_id')->references('id')->on('packages');

            $table->dropIndex(['subscribable_type_subscribable_id']);
            $table->dropColumn('subscribable_type');
            $table->dropColumn('subscribable_id');

            $table->dropColumn('next_billing_at');
        });
    }
}
