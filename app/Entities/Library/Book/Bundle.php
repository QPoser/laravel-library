<?php

namespace App\Entities\Library\Book;

use App\Entities\Library\Book;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $table = 'books_bundles';

    protected $fillable = [
        'title', 'description', 'user_id',
    ];

    public static function new(string $title, string $description, User $user)
    {
        return self::create([
            'title' => $title,
            'description' => $description,
            'user_id' => $user->id,
        ]);
    }

    public function addToBundle($book): void
    {
        if ($this->hasInBundle($book->id)) {
            throw new \DomainException('This book is already added to bundle.');
        }
        $this->books()->attach($book->id);
    }

    public function removeFromBundle($book): void
    {
        $this->books()->detach($book->id);
    }

    public function hasInBundle($id): bool
    {
        return $this->books()->where('id', $id)->exists();
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_bundles_assignment', 'bundle_id', 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
