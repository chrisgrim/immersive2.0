<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('largeImagePath')->nullable();
            $table->string('thumbImagePath')->nullable();
            $table->string('gravatar')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->char('type', 1)->default('g');
            $table->char('newsletter_type', 1)->default('n');
            $table->char('silence', 1)->default('n');
            $table->char('unread', 1)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
