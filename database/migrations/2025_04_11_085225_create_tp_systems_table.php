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
        Schema::create('tp_systems', function (Blueprint $table) {
            $table->id('id_system');
            // thông tin liên hệ
            $table->string('email', 50);
            $table->string('email_alert', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('footer_vn')->nullable();
            $table->text('footer_en')->nullable();
            // socical media
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('zalo')->nullable();

            // Seo website
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('name_vn')->nullable();
            $table->text('description')->nullable();
            $table->text('keyword')->nullable();

            // Contact page text settings
            $table->string('contact_title_vn')->nullable();
            $table->text('contact_desc_vn')->nullable();
            $table->string('contact_title_en')->nullable();
            $table->text('contact_desc_en')->nullable();

            $table->text('map')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_systems');
    }
};
