<?php

namespace App\ArticleProviders;

use App\Exceptions\NewsApiException;
use App\Models\Article;
use App\Models\Author;
use App\Models\Source;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsApi extends ArticleProvider
{
    private string $baseUrl;

    private string $apiKey;

    private string $domains;

    private int $perPage;

    private string $fromDate;

    private string $toDate;

    public function import(): void
    {
        $this->initConfigs();
        $this->initDates();

        $this->importPage(1);
    }

    private function initConfigs(): void
    {
        $this->baseUrl = config('services.newsapi.base-url');
        $this->apiKey = config('services.newsapi.api-key');
        $this->domains = config('services.newsapi.domains');
        $this->perPage = config('services.newsapi.page-chunk');

        if ((! $this->baseUrl) || (! $this->apiKey) || (! $this->perPage) || (! $this->domains)) {
            throw NewsApiException::invalidConfigs();
        }
    }

    private function initDates(): void
    {
        $this->toDate = date('Y-m-d');
        $this->fromDate = date('Y-m-d', now()->subDay()->timestamp);
    }

    private function importPage(int $page): void
    {
        echo "page: {$page} \n";

        $url = $this->baseUrl.'/v2/everything';
        $params = [
            'apiKey' => $this->apiKey,
            'domains' => $this->domains,
            'pageSize' => $this->perPage,
            'from' => $this->fromDate,
            'to' => $this->toDate,
            'page' => $page,
        ];

        // TODO: try-catch or check if success + log
        $response = Http::get($url, $params)->json();

        $articles = Arr::get($response, 'articles', []);

        foreach ($articles as $articleData) {
            $hash = $this->createHashIfNotExists($articleData['url']);

            if (! $hash) {
                continue;
            }

            $source = $this->findOrCreateSource($articleData['source']['name']);
            $article = $this->importArticle($source, $hash, $articleData);
            $this->importAuthor($articleData['author'] ?? '', $article->id);
        }

        if ($this->hasNextPage(Arr::get($response, 'totalResults'), $page)) {
            $this->importPage(++$page);
        }
    }

    private function findOrCreateSource(string $name): Source
    {
        return Source::firstOrCreate([
            'name' => $name,
        ]);
    }

    private function importArticle(Source $source, string $hash, array $data): Article
    {

        return $source->articles()->create([
            'hash' => $hash,
            'title' => Str::limit($data['title'], 250),
            'description' => $data['description'],
            'content' => $data['content'],
            'url' => $data['url'],
            'published_at' => now(), // TODO: this should be null => later admins publish them + we must have a global scope on this...
        ]);
    }

    private function importAuthor(string $name, int $articleId): void
    {
        if (empty($name)) {
            return;
        }

        $author = Author::firstOrCreate([
            'name' => $name,
        ]);

        $author->articles()->attach($articleId);
    }

    private function hasNextPage(int $all, int $currentPage): bool
    {
        return $all > ($currentPage * $this->perPage);
    }
}
