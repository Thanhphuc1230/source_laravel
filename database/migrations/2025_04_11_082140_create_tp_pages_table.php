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
            $table->string('slug')->unique();
            $table->longText('content_vn');
            $table->longText('content_en')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('footer')->default(0);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->string('image')->nullable();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->timestamps();

            // Add indexes
            $table->index('slug');
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
