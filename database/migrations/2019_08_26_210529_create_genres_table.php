<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name')->unique();
            $table->boolean('admin')->default(0);
            $table->integer('rank')->default(0);
            $table->char('type', 1)->default('t');
            $table->timestamps();
        });
        Schema::create('event_genre', function(Blueprint $table) {
            $table->foreignId('event_id');
            $table->foreignId('genre_id');
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
        Schema::dropIfExists('genres');
        Schema::dropIfExists('event_genre');
    }
}
