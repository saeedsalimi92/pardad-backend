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
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_url')->unique(); // بدون دامنه
            $table->string('destination_url');
            $table->unsignedSmallInteger('status_code')->default(301); // یا 302
            $table->enum('status', \App\Enums\Status::statuses())->default(\App\Enums\Status::InActive->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
