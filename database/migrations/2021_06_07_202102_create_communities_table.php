<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->nullable();
            $table->mediumText('blurb')->nullable();
            $table->string('largeImagePath')->nullable();
            $table->string('thumbImagePath')->nullable();
            $table->string('instagramHandle')->nullable();
            $table->string('twitterHandle')->nullable();
            $table->string('facebookHandle')->nullable();
            $table->string('patreon')->nullable();
            $table->char('status', 1)->default('d');
            $table->timestamps();
        });
        Schema::create('community_user', function(Blueprint $table) {
            $table->foreignId('community_id');
            $table->foreignId('user_id');
            $table->primary(['community_id', 'user_id']);
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
        Schema::dropIfExists('communities');
    }
}
