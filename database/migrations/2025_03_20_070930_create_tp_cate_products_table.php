<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tp_cate_products', function (Blueprint $table) {
            $table->id('id_cate_product');
            $table->uuid()->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug');
            $table->string('avatar')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('home')->default(false);
            $table->unsignedInteger('stt')->default(0)->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();

            // Add indexes
            $table->index('slug');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_cate_products');
    }
};
