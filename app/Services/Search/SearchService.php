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
use App\Http\Requests\Library\Book\BookSearchRequest;
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

    public function searchByAdmin(Request $request, int $perPage, int $page = 1): Paginator
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

        return new LengthAwarePaginator($books->forPage($page, $perPage), $books->count(), $perPage, $page);
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

        return new LengthAwarePaginator($items->forPage($page, $perPage), $response['hits']['total'], $perPage, $page);
    }

}