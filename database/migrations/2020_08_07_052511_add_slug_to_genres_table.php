<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddSlugToGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('genres', 'slug'))
        {
            Schema::table('genres', function (Blueprint $table) {
                $table->string('slug');
            });
            DB::table('genres')->get()->each(function ($post) {
                DB::table('genres')->where('id', $post->id)->update(['slug' =>  Str::slug($post->name)]);
            });
            Schema::table('genres', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
