<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 09.08.18
 * Time: 16:06
 */

namespace App\Services\Library;


use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use App\Http\Requests\Library\Book\BookCreateRequest;
use App\Http\Requests\Library\Book\BookUpdateRequest;
use App\User;
use Auth;
use DB;

class BookService
{

    /**
     * @var AuthorService
     */
    private $authorService;
    /**
     * @var GenreService
     */
    private $genreService;

    public function __construct(AuthorService $authorService, GenreService $genreService)
    {
        $this->authorService = $authorService;
        $this->genreService = $genreService;
    }

    public function create(BookCreateRequest $request, User $user, bool $isActive = false): Book
    {
        return DB::transaction(function () use ($request, $user, $isActive) {

            if (is_string($request->author) && !is_integer($request->author)) {
                $author = $this->authorService->create($request->author);
            } else {
                $author = Author::findOrFail($request->author);
            }

            if (is_string($request->genre) && !is_integer($request->author)) {
                $genre = $this->genreService->create($request->genre);
            } else {
                $genre = Genre::findOrFail($request->genre);
            }

            $path = $request->file('file')->store('books', 'public');

            $book = Book::make([
                'title' => $request->title,
                'description' => $request->description,
                'author_id' => $author->id,
                'genre_id' => $genre->id,
                'user_id' => $user->id,
                'file_path' => $path,
                'status' => $isActive ? Book::STATUS_ACTIVE : Book::STATUS_WAIT,
            ]);

            $book->saveOrFail();

            return $book;
        });
    }

    public function update(BookUpdateRequest $request, Book $book)
    {
        DB::transaction(function () use ($request, $book) {

            $author = Author::findOrFail($request->author);
            $genre = Author::findOrFail($request->genre);

            $path = false;

            if ($request->has('file')) {
                $path = $request->file('file')->store('books', 'public');
            }

            $file_path = $path ? ['file_path' => $path] : [];

            $book->update($request->only('title', 'description', 'author', 'genre', 'file') + $file_path);
        });
    }

    public function remove(Book $book)
    {
        $book->delete();
    }

    public function activate(Book $book)
    {
        $book->setActive();
        if (!$book->author->isActive()) {
            $book->author->setActive();
        }
        if (!$book->genre->isActive()) {
            $book->genre->setActive();
        }
    }

    public function deactivate(Book $book)
    {
        $book->setInactive();
    }

}