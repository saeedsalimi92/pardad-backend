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
        Schema::table('product_contents', function (Blueprint $table) {
            // ایندکس ترکیبی روی locale و slug
            $table->unique(['locale', 'slug'], 'product_contents_locale_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_contents', function (Blueprint $table) {
            $table->dropUnique('product_contents_locale_slug_unique');
        });
    }
};
