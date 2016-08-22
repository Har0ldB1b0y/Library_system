<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = ['title',
      'publisher',
      'published_year',
      'card_number',
      'call_number',
      'quantity',
      'material_id'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

}
