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
        Schema::create('tp_comments', function (Blueprint $table) {
            $table->id('id_comment');
            $table->uuid();
            $table->string('name');
            $table->string('email');
            $table->text('content');
            $table->integer('id_post');
            $table->integer('type_post')->comment('Thuộc dạng bài viết nào:1: tin tức, 2: sản phẩm, 3: trang tĩnh');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_comments');
    }
};
