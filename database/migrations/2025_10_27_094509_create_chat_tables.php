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
        // Chat sessions table
        Schema::create('tp_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id')->nullable(); // For registered users
            $table->string('guest_name')->nullable(); // For guest users
            $table->string('guest_email')->nullable(); // For guest users
            $table->string('session_id')->unique()->nullable(); // Unique session identifier
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['status', 'last_message_at']);
            $table->index('uuid');
        });

        // Chat messages table
        Schema::create('tp_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('session_id');
            $table->uuid('session_uuid');
            $table->enum('sender_type', ['user', 'admin']); // Who sent the message
            $table->unsignedBigInteger('sender_id')->nullable(); // User ID or Admin ID (nullable for guests)
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('tp_chat_sessions')->onDelete('cascade');
            $table->index(['session_id', 'created_at']);
            $table->index(['sender_type', 'sender_id']);
            $table->index('uuid');
            $table->index('session_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_chat_messages');
        Schema::dropIfExists('tp_chat_sessions');
    }
};