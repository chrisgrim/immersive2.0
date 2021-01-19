<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('level')->unique();
            $table->boolean('admin')->default(0);
            $table->integer('rank')->default(0);
            $table->timestamps();
        });
        Schema::create('contact_level_event', function(Blueprint $table) {
            $table->foreignId('event_id');
            $table->foreignId('contact_level_id');
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
        Schema::dropIfExists('contact_levels');
    }
}
