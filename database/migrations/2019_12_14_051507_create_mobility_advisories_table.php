<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilityAdvisoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobility_advisories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('mobilities')->unique();
            $table->boolean('admin')->default(0);
            $table->integer('rank')->default(0);
            $table->timestamps();
        });
        Schema::create('event_mobility_advisory', function(Blueprint $table) {
            $table->foreignId('event_id');
            $table->foreignId('mobility_advisory_id');
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
        Schema::dropIfExists('mobility_advisories');
    }
}
