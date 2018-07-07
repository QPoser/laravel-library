<?php

namespace App\Console\Commands\Search;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

class InitCommand extends Command
{
    protected $signature = 'search:init';

    protected $description = 'Init search';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    public function handle()
    {
        try {
            $this->client->indices()->delete([
                'index' => 'app',
            ]);
        } catch (Missing404Exception $e) {}

        $this->client->indices()->create([
            'index' => 'app',
            'body' => [
                'mappings' => [
                    'book' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'published_at' => [
                                'type' => 'date',
                            ],
                            'title' => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'genre' => [
                                'type' => 'integer',
                            ],
                            'author' => [
                                'type' => 'integer',
                            ],
                            'status' => [
                                'type' => 'keyword',
                            ],
                        ],
                    ],
                ],
                'settings' => [
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and '
                                ],
                            ],
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                            'trigrams' => [
                                'type' => 'ngram',
                                'min_gram' => 4,
                                'max_gram' => 6,
                            ],
                        ],
                        'analyzer' => [
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace',
                                ],
                                'tokenizer' => 'whitespace',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter',
                                    'trigrams',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }


}
