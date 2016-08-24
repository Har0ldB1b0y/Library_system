<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_years', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('student_level_id');
            $table->timestamps();
        });


        $levels =[
            '1' => [
                'kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'
            ],
            '2' => ['Grade 7','Grade 8','Grade 9','Grade 10'],
            '3' => ['Grade 11','Grade 12'],
            '4' => ['1st Year', '2nd Year'],
            '5' => ['1st Year', '2nd Year','3rd Year', '4th Year','5th Year'],
        ];

        foreach ($levels as $id =>$YearLevels){
           foreach ($YearLevels as $yearLevel){
            \App\Models\StudentYear::create([
                'name' => $yearLevel,
                'student_level_id' => $id,
            ]) ;
           }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('student_years');
    }
}
