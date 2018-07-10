<?php

namespace App\Http\Controllers\Library;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Bundle;
use App\Entities\Library\Book\Genre;
use App\Services\Search\SearchService;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{

    private $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'genre' => 'nullable|int',
            'author' => 'nullable|int',
        ]);

//        $query = Book::active()->orderByDesc('id');
//
////        if (!empty($value = $request->get('search'))) {
////            $query->where('title', 'like', '%' . $value . '%');
////        }
////
////        if (!empty($value = $request->get('genre'))) {
////            $query->where('genre_id', $value);
////        }
////
////        if (!empty($value = $request->get('author'))) {
////            $query->where('author_id', $value);
////        }
//
//        $books = $query->paginate(20);
//
        $genres = Genre::active()->get();
        $authors = Author::active()->get();

        $books = $this->search->search($request, 20, $request->get('page', 1));

        $bundles = Bundle::all();

        return view('library.books.index', compact('books', 'bundles', 'genres', 'authors'));
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
