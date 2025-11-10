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
        Schema::create('tp_brands', function (Blueprint $table) {
            $table->id('id_brand');
            $table->uuid();
            $table->string('name_vn');
            $table->boolean('status')->default(true);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('tp_brands');
    }
};
