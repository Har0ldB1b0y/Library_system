<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'gender',
        'student_level_id',
        'year_level',
        'user_id'
    ];

    public function user()
    {
       return $this->belongsTo(User::class,'user_id');
    }

    public function StudentLevel()
    {
        return $this->belongsTo(StudentLevel::class,'student_level_id');
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->middle_name.' '.$this->last_name.' '.$this->suffix;
    }
}
