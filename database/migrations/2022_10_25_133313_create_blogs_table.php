<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('en_title');
            $table->string('subtitle');
            $table->string('en_subtitle');
            $table->longText('description');
            $table->longText('en_description');
            $table->string('status');
            $table->string('instagram');
            $table->string('telegram');
            $table->string('author');
            $table->string('whatsapp');
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
