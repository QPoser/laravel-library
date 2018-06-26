<?php

namespace App\Http\Controllers\Library\Book;

use App\Entities\Library\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $stars = Book\Review::starsList();

        $this->validate($request, [
             'review' => 'required|string',
             'stars' => 'required|integer|min:1|max:5',
        ]);

        $review = Book\Review::new($request->review, $request->stars, \Auth::user()->id, $book->id);

        return redirect()->route('library.books.show', $book);
    }
}
