<?php

namespace App\Entities\Library;

use App\Entities\Library\Book\Appeal;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use App\Entities\Library\Book\Review;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @property int $id
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

    public static function statusList()
    {
        return [
            Book::STATUS_WAIT,
            Book::STATUS_ACTIVE,
            Book::STATUS_CANCELED,
        ];
    }

    public function appeal()
    {
        $starsCount = 0;

        foreach ($this->reviews as $review) {
            $starsCount += $review->stars;
        }

        if ($starsCount > 0) {
            return $starsCount / $this->reviews->count();
        }

        return 0;
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

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'book_id', 'id');
    }

    public function setActive(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('This book is already active');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function setInactive(): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('This book is already inactive');
        }
        $this->update([
            'status' => self::STATUS_WAIT,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
