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
        Schema::create('tp_cate_projects', function (Blueprint $table) {
            $table->id('id_cate_project');
            $table->uuid()->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->string('slug_vn');
            $table->string('slug_en')->nullable();
            $table->string('image_vn')->nullable();
            $table->string('image_en')->nullable();
            $table->string('keyword_vn')->nullable();
            $table->string('keyword_en')->nullable();
            $table->string('description_vn')->nullable();
            $table->string('description_en')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('home')->default(false);
            $table->unsignedInteger('stt')->default(0)->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();

            // Essential indexes for hierarchy
            $table->index('slug_vn');              // Required for route resolution
            $table->index('slug_en');              // Required for route resolution
            $table->index('uuid');                 // Required for lookups

            // Composite indexes for hierarchical queries
            $table->index(['status', 'slug_vn']);   // Route resolution optimization
            $table->index(['status', 'slug_en']);   // Route resolution optimization
            $table->index(['status', 'parent_id', 'stt']); // Covers: status + hierarchy + ordering
            $table->index(['parent_id', 'stt']);          // Covers: hierarchy navigation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_cate_projects');
    }
};
