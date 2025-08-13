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
        Schema::create('tp_news', function (Blueprint $table) {
            $table->id('id_new');
            $table->uuid('uuid')->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('intro_vn');
            $table->text('intro_en')->nullable();
            $table->longText('content_vn');
            $table->longText('content_en')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->boolean('status')->default(true);
            $table->boolean('home')->default(false);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')->references('id_cate_new')->on('tp_cate_news')->onDelete('cascade');

            // Essential indexes only  
            $table->index('slug');              // Required for route resolution
            $table->index('uuid');              // Required for lookups
            
            // Composite indexes (cover single column usage too)
            $table->index(['status', 'stt']);       // Covers: status filtering + ordering
            $table->index(['status', 'category_id']); // Covers: status + category filtering  
            $table->index(['views', 'status']);     // Covers: popular content + status
            $table->index(['created_at', 'status']); // Covers: latest news + status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_news');
    }
};
