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
        Schema::create('practice_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained('practices')->onDelete('cascade');
            $table->foreignId('word_id')->constrained('words')->onDelete('cascade');
            $table->string('correct_answer')->nullable();
            $table->string('selected_option')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_answers');
    }
};
