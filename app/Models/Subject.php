<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function getSubjects()
    {
        return $this->orderBy('name', 'ASC')->lists('name', 'name')->toArray();
    }
}
