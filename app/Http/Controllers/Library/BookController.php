<?php

namespace App\Http\Controllers\Library;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Appeal;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Bundle;
use App\Entities\Library\Book\Genre;
use App\Http\Requests\Library\Book\BookSearchRequest;
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

    public function index(BookSearchRequest $request)
    {
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

        return view('library.books.show', compact('book', 'user', 'stars'));
    }

    public function addAppeal(Book $book)
    {
        return view('library.books.appeal', compact('book'));
    }


    public function storeAppeal(Request $request, Book $book)
    {
        $this->validate($request, [
            'reason' => 'string|max:300',
        ]);

        $appeal = Appeal::new($request->get('reason'), $book, Auth::user());

        return redirect()->route('library.books.show', compact('book'))->with('success', 'Your appeal has been successfully added.');
    }
}
