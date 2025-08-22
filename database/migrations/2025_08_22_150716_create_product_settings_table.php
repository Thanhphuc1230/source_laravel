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
        Schema::create('product_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('key')->unique(); // Khóa setting như 'products_per_page', 'products_per_row', 'shipping_policy'
            $table->text('value'); // Giá trị setting (có thể là JSON hoặc text)
            $table->string('type')->default('text'); // Loại dữ liệu: text, number, json, html
            $table->string('group')->default('general'); // Nhóm setting: display, policy, general
            $table->string('description')->nullable(); // Mô tả setting
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            $table->timestamps();

            $table->index(['group', 'is_active']);
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_settings');
    }
};
