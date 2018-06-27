<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $table = 'books_bundles';

    protected $fillable = [
        'title', 'description', 'user_id',
    ];

    public static function new(string $title, string $description, int $user_id)
    {
        return self::create([
            'title' => $title,
            'description' => $description,
            'user_id' => $user_id,
        ]);
    }

    public function addToBundle($id): void
    {
        if ($this->hasInBundle($id)) {
            throw new \DomainException('This book is already added to bundle.');
        }
        $this->books()->attach($id);
    }

    public function removeFromBundle($id): void
    {
        $this->books()->detach($id);
    }

    public function hasInBundle($id): bool
    {
        return $this->books()->where('id', $id)->exists();
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_bundles_assignment', 'bundle_id', 'book_id');
    }
}
