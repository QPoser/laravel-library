<?php

namespace App\Http\Controllers\Library;

use App\Entities\Library\Book;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return view('library.books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $user = Auth::user();

        return view('library.books.show', compact('book', 'user'));
    }
}
