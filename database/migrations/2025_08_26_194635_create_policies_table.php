<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // privacy | terms
            $table->longText('content')->nullable();
            $table->longText('content_en')->nullable();
            $table->timestamps();
        });

        // إضافة سجلات افتراضية
        \DB::table('policies')->insert([
            [
                'type' => 'privacy',
                'content' => null,
                'content_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'terms',
                'content' => null,
                'content_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};

