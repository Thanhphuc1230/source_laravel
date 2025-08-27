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
        Schema::create('tp_order_shipping', function (Blueprint $table) {
            $table->id('id_order_shipping');
            $table->uuid('uuid_order_shipping');
            $table->string('f_name_order');
            $table->string('l_name_order');
            $table->string('phone', 20);
            $table->string('email');
            $table->string('address');
            $table->string('note')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_order_shipping');
    }
};
