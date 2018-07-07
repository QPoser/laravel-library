<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    protected $description = 'Reindex search';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //
    }
}
