<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // Thêm cột publish_at vào bảng user_catalogues
    public function up(): void
    {
        Schema::table('user_catalogues', function (Blueprint $table) {
            $table->tinyInteger('publish')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_catalogues', function (Blueprint $table) {
            $table->dropColumn('publish');
        });
    }
};
