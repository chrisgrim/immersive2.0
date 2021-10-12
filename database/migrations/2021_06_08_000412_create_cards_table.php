<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id');
            $table->foreignId('event_id')->nullable();
            $table->foreignId('organizer_id')->nullable();
            $table->string('name')->nullable();
            $table->mediumText('blurb')->nullable();
            $table->string('url')->nullable();
            $table->string('thumbImagePath')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->char('type', 1)->default('b');
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
        Schema::dropIfExists('cards');
    }
}
