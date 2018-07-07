<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 08.07.18
 * Time: 2:52
 */

namespace App\Services\Search;


use App\Entities\Library\Book;
use Elasticsearch\Client;

class BookIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'app',
            'type' => 'book',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Book $book): void
    {
        $this->client->index([
            'index' => 'app',
            'type' => 'book',
            'id' => $book->id,
            'body' => [
                'id' => $book->id,
                'published_at' => $book->published_at ? $book->published_at->format(DATE_ATOM) : null,
                'title' => $book->title,
                'description' => $book->description,
                'status' => $book->status,
                'genre' => $book->genre_id,
                'author' => $book->author_id,
            ],
        ]);
    }

    public function remove(Book $book): void
    {
        $this->client->delete([
            'index' => 'app',
            'type' => 'book',
            'id' => $book->id,
        ]);
    }

}