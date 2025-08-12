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
            $table->dropUnique('redirects_source_url_unique');

            // اضافه کردن ایندکس ساده
            $table->index('source_url');

            // اضافه کردن ستون soft delete
            $table->softDeletes();
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
            $table->dropIndex(['source_url']);

            // برگرداندن ایندکس unique
            $table->unique('source_url');

            // حذف ستون soft delete
            $table->dropSoftDeletes();
        });
    }
};
