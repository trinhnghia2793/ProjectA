<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // Tạo bảng post_catalogue_translate (post_catalogues n - n languages)
    // Sau này tên bảng được đổi thành post_catalogue_language
    public function up(): void
    {
        Schema::create('post_catalogue_translate', function (Blueprint $table) {
            $table->unsignedBigInteger('post_catalogue_id');
            $table->foreign('post_catalogue_id')->references('id')->on('post_catalogues')->onDelete('cascade');
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->string('name');
            $table->text('description'); // chỗ này đã được sửa tay thành nullable()
            $table->longText('content'); // chỗ này đã được sửa tay thành nullable()
            $table->string('meta_title'); // chỗ này đã được sửa tay thành nullable()
            $table->string('meta_keyword'); // chỗ này đã được sửa tay thành nullable()
            $table->text('meta_description'); // chỗ này đã được sửa tay thành nullable()
        });
        // quên cài nullable cho mấy trường này :v
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_catalogue_translate');
    }
};
