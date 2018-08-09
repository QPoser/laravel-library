<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    protected $table = 'books_genres';

    protected $fillable = [
        'name', 'status',
    ];

    public static function statusList()
    {
        return [
            self::STATUS_WAIT,
            self::STATUS_ACTIVE,
        ];
    }

    public function setActive(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('This genre is already active');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function setInactive(): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('This genre is already inactive');
        }
        $this->update([
            'status' => self::STATUS_WAIT,
        ]);
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'genre_id', 'id');
    }
}
