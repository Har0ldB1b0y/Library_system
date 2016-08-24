<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLevel extends Model
{
    protected $fillable = ['name', 'description'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }


    public function getLevels()
    {
        return $this->lists('name','id')->toArray();
    }
    
}
