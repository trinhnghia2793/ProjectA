<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // Tạo bảng post_catalogues (loại bài viết)
    public function up(): void
    {
        Schema::create('post_catalogues', function (Blueprint $table) {
            $table->id();
            $table->integer('parentid')->default(0); // sau này bị sửa thành parent_id(sửa chay)
            $table->integer('lft')->default(0); // left
            $table->integer('rgt')->default(0); // right
            $table->integer('level')->default(0); // level
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->text('album')->nullable();
            $table->tinyInteger('publish')->default(1); // 1: Unpublish, 2: Publish
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Khóa ngoại trỏ đến user
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_catalogues');
    }
};
