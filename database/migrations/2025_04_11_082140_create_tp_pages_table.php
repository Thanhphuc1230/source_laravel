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
        Schema::create('tp_pages', function (Blueprint $table) {
            $table->id('id_page');
            $table->uuid()->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug_vn')->unique();
            $table->string('slug_en')->nullable()->unique();
            $table->longText('content_vn');
            $table->longText('content_en')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('footer')->default(0);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->string('keyword_vn')->nullable();
            $table->string('keyword_en')->nullable();
            $table->string('description_vn')->nullable();
            $table->string('description_en')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();

            // Essential indexes only
            $table->index('slug_vn');              // Required for route resolution
            $table->index('slug_en');              // Required for route resolution
            $table->index(['status', 'slug_vn']);   // Route resolution optimization
            $table->index(['status', 'slug_en']);   // Route resolution optimization
            $table->index(['status', 'stt']);   // Covers: status filtering + ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_pages');
    }
};
