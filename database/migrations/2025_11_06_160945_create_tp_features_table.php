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
        Schema::create('tp_features', function (Blueprint $table) {
            $table->id('id_feature');
            $table->uuid();
            $table->string('title_vn');
            $table->text('content_vn');
            $table->boolean('status')->default(true);
            $table->string('image')->nullable(); // Có thể dùng cho SVG hoặc image
            $table->unsignedInteger('stt')->default(0)->nullable();
            $table->timestamps();

            // Essential index for main query pattern
            $table->index(['status', 'stt']);   // Covers: status filtering + ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_features');
    }
};
