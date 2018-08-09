<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use App\Http\Requests\Library\Book\BookCreateRequest;
use App\Http\Requests\Library\Book\BookUpdateRequest;
use App\Services\Library\BookService;
use App\Services\Search\SearchService;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{

    /**
     * @var SearchService
     */
    private $service;
    /**
     * @var BookService
     */
    private $bookService;

    public function __construct(SearchService $service, BookService $bookService)
    {
        $this->service = $service;
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        $books = $this->service->searchByAdmin($request, 30, $request->get('page', 1));

        $statuses = Book::statusList();

        $authors = Author::all();
        $genres = Genre::all();

        return view('admin.books.home', compact('books', 'statuses', 'authors', 'genres'));
    }

    public function create()
    {
        $authors = Author::all();
        $genres = Genre::all();

        if ($authors->isEmpty()) {
            return redirect()->route('admin.books.index')->with('warning', 'No authors, add an author to create a book');
        }

        if ($genres->isEmpty()) {
            return redirect()->route('admin.books.index')->with('warning', 'No genres, add an genre to create a book');
        }

        return view('admin.books.create', compact('genres', 'authors'));
    }

    public function store(BookCreateRequest $request)
    {
        $book = $this->bookService->create($request, Auth::user(), true);

        return redirect()->route('admin.books.show', $book)->with('success', 'Book successfully created!');
    }

    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $genres = Genre::all();

        return view('admin.books.edit', compact('book', 'authors', 'genres'));
    }

    public function update(BookUpdateRequest $request, Book $book)
    {
        $this->bookService->update($request, $book);

        return redirect()->route('admin.books.show', $book)->with('success', 'Book successfully edited!');
    }

    public function destroy(Book $book)
    {
        $this->bookService->remove($book);

        return redirect()->route('admin.books.index')->with('success', 'Book ' . $book->title . ' is successfully deleted.');
    }

    public function setActive(Book $book)
    {
        try {
            $this->bookService->activate($book);
        } catch (\DomainException $e) {
            return redirect()->route('admin.books.show', $book)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.books.show', $book)->with('success', 'Success! This book is active!');
    }

    public function setInactive(Book $book)
    {
        try {
            $this->bookService->deactivate($book);
        } catch (\DomainException $e) {
            return redirect()->route('admin.books.show', $book)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.books.show', $book)->with('success', 'Success! This book is inactive!');
    }
}
