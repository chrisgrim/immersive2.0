<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('rating')->default(0);
            $table->string('largeImagePath')->nullable();
            $table->string('thumbImagePath')->nullable();
            $table->string('instagramHandle')->nullable();
            $table->string('twitterHandle')->nullable();
            $table->string('facebookHandle')->nullable();
            $table->char('type', 1)->default('o');
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
        Schema::dropIfExists('organizers');
    }
}
