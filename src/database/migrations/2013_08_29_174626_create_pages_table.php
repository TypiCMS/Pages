<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('meta_robots_no_index')->default(0);
            $table->string('meta_robots_no_follow')->default(0);
            $table->string('image')->nullable();
            $table->integer('position')->unsigned()->default(0);
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->boolean('private')->default(0);
            $table->boolean('is_home')->default(0);
            $table->boolean('redirect')->default(0);
            $table->boolean('no_cache')->default(0);
            $table->text('css')->nullable();
            $table->text('js')->nullable();
            $table->string('module')->nullable();
            $table->string('template')->nullable();
            $table->json('slug');
            $table->json('uri');
            $table->json('title');
            $table->json('body');
            $table->json('status');
            $table->json('meta_title');
            $table->json('meta_keywords');
            $table->json('meta_description');
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
