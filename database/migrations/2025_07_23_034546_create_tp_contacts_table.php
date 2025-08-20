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
        Schema::create('tp_contacts', function (Blueprint $table) {
            $table->id('id_contact');
            $table->uuid();
            $table->string('fullname');
            $table->string('email');
            $table->string('phone');
            $table->string('subject');
            $table->string('message');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_contacts');
    }
};
