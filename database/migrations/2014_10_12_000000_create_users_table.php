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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('fullname', 255); 
            $table->string('username', 100)->nullable(); 
            $table->string('email', 255)->unique(); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',80)->nullable();
            $table->unsignedInteger('level')->default(3)->comment('1:Admin - 2:Staff - 3:User');
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
