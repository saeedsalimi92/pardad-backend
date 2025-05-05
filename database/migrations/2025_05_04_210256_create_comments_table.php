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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // نام کامنت‌دهنده
            $table->string('last_name');  // نام خانوادگی کامنت‌دهنده
            $table->string('mobile');  // موبایل کامنت‌دهنده
            $table->string('email');  // ایمیل کامنت‌دهنده
            $table->text('comment');  // محتوا
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');  // شناسه کاربر (فقط برای کامنت‌های پاسخ)// شناسه کامنت والد
            $table->boolean('is_reply')->default(false);  // آیا پاسخ است یا نه
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending'); // اضافه کردن فیلد status


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
