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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->boolean('allow_multiple_votes')->default(false);
            $table->boolean('show_results_before_voting')->default(true);
            $table->integer('total_votes')->default(0);
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');
            $table->dateTime('closed_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
