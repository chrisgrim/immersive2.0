<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountriesToCityListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('city_lists', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->integer('rank')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('city_lists', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('rank');
        });
    }
}
