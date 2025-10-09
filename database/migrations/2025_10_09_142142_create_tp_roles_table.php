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
        Schema::create('tp_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, manager, staff
            $table->string('display_name'); // Quản trị viên, Quản lý
            $table->text('description')->nullable(); // mô tả vai trò
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_roles');
    }
};
