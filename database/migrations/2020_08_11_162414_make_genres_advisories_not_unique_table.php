<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeGenresAdvisoriesNotUniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropUnique('genres_name_unique');
        });
        Schema::table('mobility_advisories', function (Blueprint $table) {
            $table->dropUnique('mobility_advisories_mobilities_unique');
        });
        Schema::table('content_advisories', function (Blueprint $table) {
            $table->dropUnique('content_advisories_advisories_unique');
        });
        Schema::table('remote_locations', function (Blueprint $table) {
            $table->dropUnique('remote_locations_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            //
        });
    }
}
