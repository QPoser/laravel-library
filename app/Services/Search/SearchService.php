<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 08.07.18
 * Time: 17:01
 */

namespace App\Services\Search;


use App\Entities\Library\Book;
use App\Entities\Library\Book\Author;
use App\Entities\Library\Book\Genre;
use Elasticsearch\Client;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(Request $request, int $perPage, int $page): Paginator
    {
        $author = null;
        $genre = null;

        if ($request['author']) {
            $author = Author::findOrFail($request['author']);
        }

        if ($request['genre']) {
            $genre = Genre::findOrFail($request['genre']);
        }

        $response = $this->client->search([
            'index' => 'app',
            'type' => 'book',
            'body' => [
                '_source' => ['id'],
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                'sort' => [],
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            [
                                ['term' => ['status' => Book::STATUS_ACTIVE]]
                            ],
                            array_filter([
                                $author ? ['term' => ['author' => $author->id]] : false,
                                $genre ? ['term' => ['genre' => $genre->id]] : false,
                                !empty($request['search']) ? ['multi_match' => [
                                    'query' => $request['search'],
                                    'fields' => [ 'title^3', 'description' ],
                                ]] : false,
                            ])
                        ),
                    ]
                ],
            ],
        ]);

        $ids = array_column($response['hits']['hits'], '_id');

        if (!$ids) {
            return new LengthAwarePaginator([], 0, $perPage, $page);
        }

        $items = Book::active()
            ->with(['author', 'genre'])
            ->whereIn('id', $ids)
            ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
            ->get();

        return new LengthAwarePaginator($items, $response['hits']['total'], $perPage, $page);
    }

}