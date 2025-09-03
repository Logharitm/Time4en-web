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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المستخدم
            $table->string('email')->unique(); // البريد الإلكتروني
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            // تمييز بين المستخدم العادي والمدير
            $table->enum('role', ['user', 'admin'])->default('user');

            // إعدادات إضافية
            $table->string('language', 10)->default('en'); // اللغة المفضلة

            // إحصائيات
            $table->unsignedInteger('folders_count')->default(0);
            $table->unsignedInteger('words_count')->default(0);

            // الاشتراكات
            $table->string('subscription_plan', 50)->default('free');
            $table->date('subscription_expires_at')->nullable();



            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
