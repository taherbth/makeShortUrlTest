<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRuleToStudentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_responses', function (Blueprint $table) {
            //$table->unique(['student_id', 'course_question_id', 'student_response'],'unique_student_responses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_responses', function (Blueprint $table) {
            //$table->dropUnique('unique_student_responses');
        });
    }
}
