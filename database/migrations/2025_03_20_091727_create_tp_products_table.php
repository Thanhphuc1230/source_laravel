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
        Schema::create('tp_products', function (Blueprint $table) {
            $table->id('id_product');
            $table->uuid('uuid')->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug_vn')->unique();
            $table->string('slug_en')->nullable()->unique();
            $table->text('intro_vn');
            $table->text('intro_en')->nullable();
            $table->decimal('price', 15, 0);
            $table->decimal('price_old', 15, 0)->nullable();
            $table->longText('content_vn');
            $table->longText('content_en')->nullable();
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->longText('image_detail')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('hot')->default(false);
            $table->boolean('home')->default(false);
            $table->boolean('sale')->default(false);
            $table->unsignedInteger('stt')->default(1);
            $table->string('keyword_vn')->nullable();
            $table->string('keyword_en')->nullable();
            $table->text('description_vn')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id_cate_product')->on('tp_cate_products')->onDelete('cascade');

            // Essential indexes only
            $table->index('slug_vn');              // Required for route resolution
            $table->index('slug_en');              // Required for route resolution
            $table->index('uuid');              // Required for lookups

            // Composite indexes (cover single column usage too)
            $table->index(['status', 'stt']);       // Covers: status filtering + ordering
            $table->index(['status', 'home']);      // Covers: status + homepage products
            $table->index(['status', 'category_id']); // Covers: status + category filtering
            $table->index(['created_at', 'status']); // Covers: date ordering + status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_products');
    }
};
