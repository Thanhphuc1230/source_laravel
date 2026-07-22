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
            $table->string('title_en')->nullable();
            $table->text('content_vn');
            $table->text('content_en')->nullable();
            $table->boolean('status')->default(true);
            $table->string('image')->nullable(); // Class icon dùng chung
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
