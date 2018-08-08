<?php

namespace App\Http\Controllers\Library;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Appeal;
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

    public function addAppeal(Book $book)
    {
        return view('library.books.appeal', compact('book'));
    }

    /**
     * @param Request $request
     * @param Book $book
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeAppeal(Request $request, Book $book)
    {
        $this->validate($request, [
            'reason' => 'string|max:300',
        ]);

        $appeal = Appeal::new($request->get('reason'), $book, Auth::user());

        return redirect()->route('library.books.show', compact('book'))->with('success', 'Your appeal has been successfully added.');
    }
}
