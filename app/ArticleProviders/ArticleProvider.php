<?php

namespace App\ArticleProviders;

use App\Models\Article;

abstract class ArticleProvider
{
    abstract public function import(): void;

    protected function createHashIfNotExists(string $key): bool|string
    {
        $hash = hash('xxh3', $key);

        return Article::where('hash', $hash)->exists() ? false : $hash;
    }
}
