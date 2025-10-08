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
        Schema::table('redirects', function (Blueprint $table) {
            // حذف ایندکس یونیک فعلی (اگر وجود دارد)
            $table->dropUnique(['source_url']);

            // اضافه کردن ستون soft delete
            $table->softDeletes();

            // اضافه کردن ایندکس ترکیبی برای اطمینان از یونیک بودن source_url در رکوردهای غیر حذف شده
            $table->unique(['source_url', 'deleted_at']);
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redirects', function (Blueprint $table) {
            //
            $table->dropUnique(['source_url', 'deleted_at']);

            // برگرداندن ایندکس یونیک ساده
            $table->unique('source_url');

            // حذف ستون soft delete
            $table->dropSoftDeletes();
        });
    }
};
