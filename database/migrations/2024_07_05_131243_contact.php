<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //

        Schema::create('contacts', function (Blueprint $table) {


            $table->string('title');
            $table->string('en_title');
            $table->string('subtitle');
            $table->string('en_subtitle');
            $table->longText('description');
            $table->longText('en_description');
            $table->longText('address');
            $table->longText('en_address');
            $table->longText('address2');
            $table->longText('en_address2');
            $table->longText('phone');
            $table->longText('phone2');
            $table->longText('mobile');
            $table->longText('mobile2');
            $table->longText('email');
            $table->longText('email2');
            $table->longText('theme');
            $table->string('status');
            $table->json('images');
            $table->json('info');
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_title')->nullable();
            $table->string('no_index')->nullable();
            $table->longText('canonical')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('contacts');

    }
};
