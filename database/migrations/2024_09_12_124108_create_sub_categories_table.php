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
            $table->string('en_title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('en_subtitle')->nullable();
            $table->longText('description');
            $table->longText('en_description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('canonical')->nullable();
            $table->enum('no_index', ['active', 'inactive'])->default('active');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->string('url')->nullable();
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
