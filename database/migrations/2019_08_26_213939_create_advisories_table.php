<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvisoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->boolean('wheelchairReady')->nullable();
            $table->boolean('sexual')->nullable();
            $table->text('sexualDescription')->nullable();
            $table->text('audience')->nullable();
            $table->string('ageRestriction')->nullable();
            $table->text('contactAdvisories')->nullable();
            $table->timestamps();
            $table->foreign('event_id')
                    ->references('id')->on('events')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advisories');
    }
}
