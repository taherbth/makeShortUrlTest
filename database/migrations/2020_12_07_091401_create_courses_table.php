<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('details')->nullable();
            $table->string('video_original_name');
            $table->string('path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('topic')->nullable();
            $table->integer('chapter')->nullable();
            $table->integer('episode')->nullable();
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->boolean('is_public')->default(0);
            $table->boolean('is_draft')->default(0);
            $table->unsignedInteger('publishable_id');
            $table->timestamps();
            $table->unique(['topic', 'chapter', 'episode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
