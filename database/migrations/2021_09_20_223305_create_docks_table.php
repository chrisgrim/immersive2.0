<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name', 50)->nullable();
            $table->string('location', 50)->default('none');
            $table->char('type', 1)->default('f');
            $table->integer('order')->unsigned()->default(0);
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
        Schema::dropIfExists('docks');
    }
}
