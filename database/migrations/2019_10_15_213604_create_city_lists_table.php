<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('population');
            $table->string('name');
            $table->DECIMAL('latitude', 10 ,8)->nullable();
            $table->DECIMAL('longitude', 11, 8)->nullable();
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
        Schema::dropIfExists('city_lists');
    }
}
