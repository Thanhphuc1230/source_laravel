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
        Schema::create('tp_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('name');
            $table->text('message');
            $table->string('image');
            $table->tinyInteger('status')->default(0);
            $table->integer('stt')->default(0);
            $table->timestamps();
            
            // Essential indexes
            $table->index('uuid');              // Required for lookups
            $table->index(['status', 'stt']);   // Covers: status filtering + ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_feedback');
    }
};
