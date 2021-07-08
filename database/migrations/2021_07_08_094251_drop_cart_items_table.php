<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cart_items');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('cart_id')->index();
            $table->morphs('cartable');
            $table->string('type');
            $table->unsignedBigInteger('quantity')->default(1);
            $table->unsignedBigInteger('total_price')->default(0);
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts');
        });
    }
}
