<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowOnGoingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_on_goings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->boolean('mon')->default(false);
            $table->boolean('tue')->default(false);
            $table->boolean('wed')->default(false);
            $table->boolean('thu')->default(false);
            $table->boolean('fri')->default(false);
            $table->boolean('sat')->default(false);
            $table->boolean('sun')->default(false);
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
        Schema::dropIfExists('show_on_goings');
    }
}
