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
        Schema::create('tp_order_status', function (Blueprint $table) {
            $table->id('id_order_status');
            $table->uuid('uuid_order_status');
            $table->unsignedBigInteger('shipping_id');//connect with order_shipping
            $table->string('payment_method');
            $table->string('total');
            $table->tinyInteger('auth_id')->nullable();;//id of user have auth
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('shipping_id')->references('id_order_shipping')->on('tp_order_shipping');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_order_status');
    }
};
