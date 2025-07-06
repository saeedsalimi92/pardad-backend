<?php

use App\Models\Brand;
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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('code')->unique();
            $table->enum('type', \App\Enums\BrandType::types())->default(\App\Enums\BrandType::Normal->value);
            $table->integer('parent_id')->nullable();
            $table->enum('status', \App\Enums\Status::statuses())->default(\App\Enums\Status::InActive->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
