<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::orderByDesc('id');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('title'))) {
            $query->where('title', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('author_id'))) {
            $query->where('author_id', $value);
        }

        if (!empty($value = $request->get('genre_id'))) {
            $query->where('genre_id', $value);
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $books = $query->get();

        $statuses = [
            Book::STATUS_ACTIVE,
            Book::STATUS_WAIT,
            Book::STATUS_CANCELED,
        ];

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

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255|unique:books',
            'description' => 'required|string',
            'author_id' => 'required|integer',
            'genre_id' => 'required|integer',
            'file' => 'required|mimes:txt,doc,docx,fb2,pdf',
        ]);

        $author = Author::findOrFail($request->author_id);
        $genre = Author::findOrFail($request->genre_id);

        $path = $request->file('file')->store('books', 'public');

        $book = Book::new($request->title, $request->description, $author->id, $genre->id, Auth::user()->id, $path, true);

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

    public function update(Request $request, Book $book)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255|unique:books,id,' . $book->id,
            'description' => 'required|string',
            'author_id' => 'required|integer',
            'genre_id' => 'required|integer',
            'file' => 'mimes:txt,doc,docx,fb2,pdf',
        ]);

        $author = Author::findOrFail($request->author_id);
        $genre = Author::findOrFail($request->genre_id);

        $path = false;

        if ($request->has('file')) {
            $path = $request->file('file')->store('books', 'public');
        }

        $file_path = $path ? ['file_path' => $path] : [];

        $book->update($request->only('title', 'description', 'author_id', 'genre_id', 'file') + $file_path);

        return redirect()->route('admin.books.show', $book)->with('success', 'Book successfully edited!');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book ' . $book->title . ' is successfully deleted.');
    }

    public function setActive(Book $book)
    {
        try {
            $book->setActive();
            if (!$book->author->isActive()) {
                $book->author->setActive();
            }
            if (!$book->genre->isActive()) {
                $book->genre->setActive();
            }
        } catch (\DomainException $e) {
            return redirect()->route('admin.books.show', $book)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.books.show', $book)->with('success', 'Success! This book is active!');
    }

    public function setInactive(Book $book)
    {
        try {
            $book->setInactive();
        } catch (\DomainException $e) {
            return redirect()->route('admin.books.show', $book)->with('error', $e->getMessage());
        }

        return redirect()->route('admin.books.show', $book)->with('success', 'Success! This book is inactive!');
    }

    public function addAppeal(Book $book)
    {

    }
}
