<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoteLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('admin')->default(0);
            $table->integer('rank')->default(0);
            $table->char('type', 1)->default('r');
            $table->timestamps();
        });
         Schema::create('event_remote_location', function(Blueprint $table) {
            $table->foreignId('event_id');
            $table->foreignId('remote_location_id');
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
        Schema::dropIfExists('remote_locations');
        Schema::dropIfExists('event_remote_locations');
    }
}
