<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    protected $table = 'books_authors';

    protected $fillable = [
        'name', 'status',
    ];

    public static function new(string $name, $active = false): self
    {
        return self::create([
            'name' => $name,
            'status' => $active ? self::STATUS_ACTIVE : self::STATUS_WAIT,
        ]);
    }

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
            throw new \DomainException('This author is already active');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function setInactive(): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('This author is already inactive');
        }
        $this->update([
           'status' => self::STATUS_WAIT,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'author_id', 'id');
    }
}
