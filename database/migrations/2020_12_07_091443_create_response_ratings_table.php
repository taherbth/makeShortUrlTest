<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_responses_id')->unique();
            $table->unsignedInteger('rateable_id');
            $table->unsignedSmallInteger('response_rating');
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
        Schema::dropIfExists('response_ratings');
    }
}
