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
            $table->morphs("commentable");
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('set null');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('email')->nullable();
            $table->text('content');
            $table->enum("status", \App\Enums\CommentStatus::statuses())->default(\App\Enums\CommentStatus::Pending->value)->index();
            $table->timestamps();
            $table->softDeletes();
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
