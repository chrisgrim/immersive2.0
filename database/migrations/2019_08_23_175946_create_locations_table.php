<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->boolean('hiddenLocationToggle')->default(0);
            $table->string('hiddenLocation')->nullable();
            $table->string('home')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->DECIMAL('longitude', 11, 8)->nullable();
            $table->DECIMAL('latitude', 10 ,8)->nullable();
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
        Schema::dropIfExists('locations');
    }
}
