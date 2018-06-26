<?php

namespace App\Entities\Library;

use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use App\Entities\Library\Book\Review;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @property string $title
 * @property string $description
 * @property string $file_path
 * @property string $status
 * @property integer $genre_id
 * @property integer $user_id
 * @property integer $author_id
 */
class Book extends Model
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'title', 'description', 'user_id', 'genre_id', 'author_id', 'file_path', 'status'
    ];

    protected $table = 'books';

    public static function new(string $title, string $description, int $author, int $genre, int $user, string $file_path): self
    {
        return self::create([
           'title' => $title,
           'description' => $description,
           'author_id' => $author,
           'genre_id' => $genre,
           'user_id' => $user,
           'file_path' => $file_path,
           'status' => self::STATUS_WAIT,
        ]);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id', 'id');
    }

    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
