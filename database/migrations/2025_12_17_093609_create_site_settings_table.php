<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'hero_section', 'trade_partner_section'
            $table->string('type')->default('text'); // text, json, image, editor
            $table->text('value')->nullable(); // Lưu JSON hoặc text
            $table->string('group')->nullable(); // 'homepage', 'about', 'contact'
            $table->text('description')->nullable(); // Mô tả cho admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
