<?php

use Illuminate\Database\Seeder;

class StudentsLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels =[
          'Elementary',
          'Junior High',
          'Senior High',
          'Vocational',
          'College Degree',
        ];

        foreach ($levels as $level){
            \App\Models\StudentLevel::create([
               'name' => $level
            ]);
        }
    }
}
