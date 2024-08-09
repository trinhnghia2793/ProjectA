<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Thêm cột đường dẫn (canonical) vào bảng post_catalogue_language
    public function up(): void
    {
        Schema::table('post_catalogue_language', function (Blueprint $table) {
            $table->string('canonical')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_catalogue_language', function (Blueprint $table) {
            $table->dropColumn('canonical');
        });
    }
};
