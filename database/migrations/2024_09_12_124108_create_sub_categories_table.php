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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('en_title');
            $table->string('subtitle');
            $table->string('en_subtitle');
            $table->longText('description');
            $table->longText('en_description');
            $table->string('status');
            $table->string('parent_id')->nullable();
            $table->json('images');
            $table->json('logos');
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_title')->nullable();
            $table->string('no_index')->nullable();
            $table->longText('canonical')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
