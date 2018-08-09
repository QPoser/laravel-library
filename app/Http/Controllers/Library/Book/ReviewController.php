<?php

namespace App\Http\Controllers\Library\Book;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $this->validate($request, [
             'review' => 'required|string',
             'stars' => 'required|integer|min:1|max:5',
        ]);

        Review::new($request->review, $request->stars, \Auth::user()->id, $book->id);

        return redirect()->route('library.books.show', $book)->with('success', 'Your review has been successfully added.');
    }
}
