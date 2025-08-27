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
        Schema::create('tp_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->integer('visit_count')->default(0);
            $table->timestamps();

            // Essential index for analytics queries
            $table->index(['visit_date', 'visit_count']); // Covers: date filtering + count ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_analytics');
    }
};
