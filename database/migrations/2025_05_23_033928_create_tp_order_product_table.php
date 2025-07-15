<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_order_product', function (Blueprint $table) {
            $table->id('id_order_product');
            $table->uuid('uuid_order_product');
            $table->unsignedBigInteger('order_status_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->string('attribute')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id_product')->on('tp_products');

            $table->foreign('order_status_id')->references('id_order_status')->on('tp_order_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_order_product');
    }
};
