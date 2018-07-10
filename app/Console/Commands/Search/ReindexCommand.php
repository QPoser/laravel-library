<?php

namespace App\Console\Commands\Search;

use App\Entities\Library\Book;
use App\Services\Search\BookIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    protected $description = 'Reindex search';
    /**
     * @var BookIndexer
     */
    private $books;

    public function __construct(BookIndexer $books)
    {
        parent::__construct();
        $this->books = $books;
    }

    public function handle()
    {
        $this->books->clear();

        foreach (Book::active()->orderBy('id')->cursor() as $book) {
            $this->books->index($book);
        }

        return true;
    }
}
