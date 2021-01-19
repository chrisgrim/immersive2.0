<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('event_id');
            $table->foreignId('organizer_id');
            $table->string('image_path')->default('/storage/reviews/default.png');
            $table->string('reviewer_name');
            $table->string('url');
            $table->text('review');
            $table->integer('rank')->default(5);
            $table->foreign('organizer_id')
                    ->references('id')->on('organizers')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('review_events');
    }
}
