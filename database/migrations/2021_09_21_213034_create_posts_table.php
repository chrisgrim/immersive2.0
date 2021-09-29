<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->nullable();
            $table->string('blurb')->nullable();
            $table->foreignId('community_id');
            $table->foreignId('shelf_id');
            $table->foreignId('user_id');
            $table->string('largeImagePath')->nullable();
            $table->string('thumbImagePath')->nullable();
            $table->char('status', 1)->default('d');
            $table->char('type', 1)->default('s');
            $table->integer('order')->unsigned()->default(0);
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
        Schema::dropIfExists('posts');
    }
}
