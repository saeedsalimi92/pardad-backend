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
            $table->string('en_title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('en_subtitle')->nullable();
            $table->longText('description');
            $table->longText('en_description')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('canonical')->nullable();
            $table->string('url')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->enum('no_index', ['active', 'inactive'])->default('active');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->string('author')->nullable();
            $table->string('whatsapp')->nullable();


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
