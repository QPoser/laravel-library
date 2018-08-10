<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    protected $table = 'books_reviews';

    protected $fillable = [
        'review', 'stars', 'status', 'user_id', 'book_id',
    ];

    public static function starsList()
    {
        return [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
        ];
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
