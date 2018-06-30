<?php

namespace App\Http\Controllers\Cabinet;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use Auth;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $books = Book::forUser($user)->orderByDesc('id')->paginate(20);

        return view('cabinet.books.home', compact('books', 'user'));
    }

    public function create()
    {
        $authors = Author::where(['status' => Author::STATUS_ACTIVE])->get();

        $genres = Genre::where(['status' => Genre::STATUS_ACTIVE])->get();

        $user = Auth::user();

        return view('cabinet.books.create', compact('user', 'genres', 'authors'));
    }

    public function store(Request $request)
    {
        $authorValidate = [
            'author' => is_string($request->author) ? 'required|string' : 'required|integer',
        ];

        $genreValidate = [
            'genre' => is_string($request->genre) ? 'required|string' : 'required|integer',
        ];

        $this->validate($request, array_merge([
           'title' => 'required|string|max:255|unique:books',
           'description' => 'required|string',
           'file' => 'required|mimes:txt,doc,docx,fb2,pdf',
        ], $authorValidate, $genreValidate));

        if (is_string($request->author)) {
            $author = Author::new($request->author);
        } else {
            $author = Author::findOrFail($request->author);
        }

        if (is_string($request->genre)) {
            $genre = Genre::new($request->genre);
        } else {
            $genre = Genre::findOrFail($request->genre);
        }

        $path = $request->file('file')->store('books', 'public');

        $book = Book::new($request->title, $request->description, $author->id, $genre->id, Auth::user()->id, $path);

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

    public function update(Request $request, Book $book)
    {
        $authorValidate = [
            'author' => is_string($request->author) ? 'required|string' : 'required|integer',
        ];

        $genreValidate = [
            'genre' => is_string($request->genre) ? 'required|string' : 'required|integer',
        ];

        $this->validate($request, array_merge([
            'title' => 'required|string|max:255|unique:books,title,' . $book->id . ',id',
            'description' => 'required|string',
        ], $authorValidate, $genreValidate));

        $book->update($request->only([
            'title', 'description', 'author', 'genre',
        ]));
    }

    public function destroy(Book $book)
    {
        $this->checkAccess($book);

        $book->delete();

        return redirect()->route('cabinet.books.home');
    }

    private function checkAccess(Book $book): void
    {
        if (!Gate::allows('manage-own-book', $book)) {
            abort(403);
        }
    }
}
