<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('institution_name');
            $table->string('institution_profile_picture')->nullable();
            $table->string('institution_address')->nullable();
            $table->string('institution_code')->unique();
            $table->unsignedInteger('institution_primary_facilitator_quantity');
            $table->unsignedInteger('institution_primary_student_quantity');
            $table->string('admin_name')->nullable();
            $table->string('email')->unique();
            $table->string('admin_profile_picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('institutions');
    }
}
