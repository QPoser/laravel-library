<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_WAIT = 'wait';

    protected $table = 'appeals';

    protected $fillable = ['id', 'user_id', 'book_id', 'reason', 'status'];

    public static function new(string $reason, Book $book, User $user)
    {
        return self::create([
            'reason' => $reason,
            'book_id' => $book->id,
            'user_id' => $user->id,
            'status' => self::STATUS_WAIT,
        ]);
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isCanceled()
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function accept()
    {
        $this->update(['status' => self::STATUS_ACCEPTED]);
    }

    public function cancel()
    {
        $this->update(['status' => self::STATUS_CANCELED]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
