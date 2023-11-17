<?php

namespace App\Console\Commands;

use App\Enums\ArticleDataSource;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    protected $signature = 'app:fetch-articles {provider}';

    protected $description = 'Fetch articles from specific provider';

    public function handle()
    {
        ArticleDataSource::from($this->argument('provider'))
            ->provider()
            ->import();
    }
}
