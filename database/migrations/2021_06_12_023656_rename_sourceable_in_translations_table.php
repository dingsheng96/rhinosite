<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSourceableInTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('translations')) {
            Schema::table('translations', function (Blueprint $table) {
                $table->renameColumn('sourceable_type', 'translatable_type');
                $table->renameColumn('sourceable_id', 'translatable_id');
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
        if (Schema::hasTable('translations')) {
            Schema::table('translations', function (Blueprint $table) {
                //
            });
        }
    }
}
