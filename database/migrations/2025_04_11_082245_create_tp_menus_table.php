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
        Schema::create('tp_menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->uuid();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('link')->nullable();
            $table->string('slug');
            $table->string('type', 50)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // id parent
            $table->unsignedBigInteger('object_id')->nullable(); // id của table chính
            $table->unsignedBigInteger('stt')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Essential indexes for menu hierarchy
            $table->index('slug');                       // Required for route resolution
            $table->index(['status', 'parent_id', 'stt']); // Covers: active menus + hierarchy + ordering
            $table->index(['type', 'object_id']);       // Covers: menu type lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_menus');
    }
};
