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
        // Bảng tp_role_permission
        Schema::create('tp_role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('tp_roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('tp_permissions')->onDelete('cascade');
            $table->timestamps();
            
            $table->primary(['role_id', 'permission_id']);
        });

        // Bảng tp_role_user
        Schema::create('tp_role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('tp_roles')->onDelete('cascade');
            $table->timestamps();
            
            $table->primary(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_role_user');
        Schema::dropIfExists('tp_role_permission');
    }
};
