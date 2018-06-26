<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    protected $table = 'books_authors';

    protected $fillable = [
        'name', 'status',
    ];

    public static function new(string $name)
    {
        return self::create([
            'name' => $name,
            'status' => self::STATUS_WAIT,
        ]);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'id', 'author_id');
    }
}
