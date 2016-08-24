<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id')->index();
            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->string('middle_name')->index();
            $table->string('year_level')->index();
            $table->string('gender')->index();
            $table->integer('student_level_id')->index()->unsigned();
            $table->integer('user_id')->index()->unsigned();
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
        Schema::drop('students');
    }
}
