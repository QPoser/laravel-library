<?php

namespace App\Http\Controllers\Cabinet;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use App\Http\Requests\Library\Book\BookCreateRequest;
use App\Http\Requests\Library\Book\BookUpdateRequest;
use App\Services\Library\BookService;
use Auth;
use Gate;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * @var BookService
     */
    private $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $user = Auth::user();

        $books = Book::forUser($user)->orderByDesc('id')->paginate(20);

        return view('cabinet.books.home', compact('books', 'user'));
    }

    public function create()
    {
        $authors = Author::active()->get();

        $genres = Genre::active()->get();

        $user = Auth::user();

        return view('cabinet.books.create', compact('user', 'genres', 'authors'));
    }

    public function store(BookCreateRequest $request)
    {
        $book = $this->service->create($request, Auth::user());

        return redirect()->route('cabinet.books.show', $book);
    }

    public function show(Book $book)
    {
        $this->checkAccess($book);

        $user = Auth::user();

        return view('cabinet.books.show', compact('book', 'user'));
    }

    public function edit(Book $book)
    {
        $this->checkAccess($book);

        $genres = Genre::where(['status' => Genre::STATUS_ACTIVE])->orWhere(['id' => $book->genre_id])->get();
        $authors = Author::where(['status' => Author::STATUS_ACTIVE])->orWhere(['id' => $book->author_id])->get();

        return view('cabinet.books.edit', compact('book', 'genres', 'authors'));
    }

    public function update(BookUpdateRequest $request, Book $book)
    {
        $this->checkAccess($book);

        $this->service->update($request, $book);

        return redirect()->route('cabinet.books.show', $book)->with('success', 'Book ' . $book->title . ' has been successfully updated.');
    }

    public function destroy(Book $book)
    {
        $this->checkAccess($book);

        $this->service->remove($book);

        return redirect()->route('cabinet.books.home');
    }

    private function checkAccess(Book $book): void
    {
        if (!Gate::allows('manage-own-book', $book)) {
            abort(403);
        }
    }
}
