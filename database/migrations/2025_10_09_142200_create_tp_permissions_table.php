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
        Schema::create('tp_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // category.create, product.view
            $table->string('display_name'); // Tạo danh mục, Xem sản phẩm
            $table->string('group_name'); // category, product, user, order
            $table->text('description')->nullable(); // mô tả quyền
            $table->timestamps();
            
            $table->index('group_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_permissions');
    }
};
