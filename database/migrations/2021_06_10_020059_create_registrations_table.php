<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('registrations')) {
            return;
        }

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->index();
            $table->string('mobile_no')->index();
            $table->string('tel_no')->nullable();
            $table->string('reg_no')->nullable()->index()->comment('company reg no');
            $table->enum('status', ['pending', 'approved', 'rejected'])->index();
            $table->unsignedBigInteger('validate_by')->index();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('validate_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}
