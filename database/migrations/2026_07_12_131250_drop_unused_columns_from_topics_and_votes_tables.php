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
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'device_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->string('ip_address')->nullable();
            $table->string('device_type')->nullable();
        });
    }
};
