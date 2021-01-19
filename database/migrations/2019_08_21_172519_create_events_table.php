<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('interactive_level_id')->nullable();
            $table->foreignId('advisories_id')->nullable();
            $table->foreignId('organizer_id')->nullable();
            $table->string('slug')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('remote_description')->nullable();
            $table->text('tag_line')->nullable();
            $table->string('websiteUrl')->nullable();
            $table->text('show_times')->nullable();
            $table->string('ticketUrl')->nullable();
            $table->string('largeImagePath')->nullable();
            $table->string('thumbImagePath')->nullable();
            $table->json('location_latlon')->nullable();
            $table->dateTime('closingDate')->nullable();
            $table->dateTime('embargo_date')->nullable();
            $table->string('price_range')->nullable();
            $table->char('status', 1)->default('d');
            $table->char('showtype', 1)->nullable();
            $table->boolean('hasLocation')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status','closingDate', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
