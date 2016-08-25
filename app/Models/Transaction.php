<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    CONST RESERVED = 'reserved';
    CONST BORROWED = 'borrowed';

    protected $table = 'transaction';

    protected $fillable = ['book_id', 'user_id',
      'type',
      'borrowed_at',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
