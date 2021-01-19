<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToRemoteLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remote_locations', function (Blueprint $table) {
            $table->string('slug');
        });
        DB::table('remote_locations')->get()->each(function ($post) {
            DB::table('remote_locations')->where('id', $post->id)->update(['slug' => Str::slug($post->name)]);
        });
        Schema::table('remote_locations', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::table('content_advisories', function (Blueprint $table) {
            $table->string('slug');
        });
        DB::table('content_advisories')->get()->each(function ($post) {
            DB::table('content_advisories')->where('id', $post->id)->update(['slug' => Str::slug($post->advisories)]);
        });
        Schema::table('content_advisories', function (Blueprint $table) {
            $table->unique('slug');
        });
        Schema::table('mobility_advisories', function (Blueprint $table) {
            $table->string('slug');
        });
        DB::table('mobility_advisories')->get()->each(function ($post) {
            DB::table('mobility_advisories')->where('id', $post->id)->update(['slug' => Str::slug($post->mobilities)]);
        });
        Schema::table('mobility_advisories', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remote_locations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('advisories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('mobility_advisories', function (Blueprint $table) {
            $table->string('slug')->unique();
        });
    }
}
