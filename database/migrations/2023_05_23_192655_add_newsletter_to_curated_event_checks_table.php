<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewsletterToCuratedEventChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curated_event_checks', function (Blueprint $table) {
            $table->boolean('newsletter')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curated_event_checks', function (Blueprint $table) {
            $table->dropColumn('newsletter');
        });
    }
}
