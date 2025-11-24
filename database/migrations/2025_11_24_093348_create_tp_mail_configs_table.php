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
        Schema::create('tp_mail_configs', function (Blueprint $table) {
            $table->id();
            $table->string('mailer')->default('smtp'); // smtp, mailgun, ses, etc.
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->nullable(); // tls, ssl, null
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->boolean('is_active')->default(false); // Flag to use DB config or .env
            $table->json('additional_config')->nullable(); // For extra config like mailgun domain, api key, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_mail_configs');
    }
};
