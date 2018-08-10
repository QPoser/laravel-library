<?php

namespace App\Http\Controllers\Library\Book;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Review;
use App\Http\Requests\Library\ReviewRequest;
use App\Services\Library\BookService;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * @var BookService
     */
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function store(ReviewRequest $request, Book $book)
    {
        $this->bookService->addReview($book, Auth::user(), $request);

        return redirect()->route('library.books.show', $book)->with('success', 'Your review has been successfully added.');
    }
}
