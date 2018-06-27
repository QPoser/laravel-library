<?php

namespace App\Http\Controllers\Library;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Bundle;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        $bundles = Bundle::all();

        return view('library.books.index', compact('books', 'bundles'));
    }

    public function show(Book $book)
    {
        $user = Auth::user();

        $stars = Book\Review::starsList();

        $reviews = Book\Review::where(['book_id' => $book->id])->get();
        $starsCount = 0;

        foreach ($reviews as $review) {
            $starsCount += $review->stars;
        }

        $bookStars = 0;

        if ($starsCount > 0) {
            $bookStars = $starsCount / $reviews->count();
        }

        return view('library.books.show', compact('book', 'user', 'stars', 'bookStars'));
    }
}
