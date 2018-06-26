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

    public static function new(string $review, int $stars, int $user_id, int $book_id)
    {
        return self::create([
            'review' => $review,
            'stars' => $stars,
            'user_id' => $user_id,
            'book_id' => $book_id,
            'status' => self::STATUS_WAIT,
        ]);
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
