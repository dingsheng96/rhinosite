<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_currency')->index();
            $table->unsignedBigInteger('to_currency')->index();
            $table->double('rate', 15, 5);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('from_currency')->references('id')->on('currencies');
            $table->foreign('to_currency')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_rates');
    }
}
