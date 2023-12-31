<?php

namespace App\ArticleProviders;

use App\Exceptions\GuardianException;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Guardian extends ArticleProvider
{
    private string $baseUrl;

    private string $apiKey;

    private int $perPage;

    private string $fromDate;

    private string $toDate;

    private Source $source;

    public function import(): void
    {
        $this->initConfigs();
        $this->initDates();
        $this->setSource();

        $this->importPage(1);
    }

    private function initConfigs(): void
    {
        $this->baseUrl = config('services.guardian.base-url');
        $this->apiKey = config('services.guardian.api-key');
        $this->perPage = config('services.guardian.page-chunk');

        if ((! $this->baseUrl) || (! $this->apiKey) || (! $this->perPage)) {
            throw GuardianException::invalidConfigs();
        }
    }

    private function initDates(): void
    {
        $this->toDate = date('Y-m-d');
        $this->fromDate = date('Y-m-d', now()->subDay()->timestamp);
    }

    private function setSource(): void
    {
        if (! ($source = Source::where('name', 'Guardian')->first())) {
            throw GuardianException::sourceNotFound();
        }

        $this->source = $source;
    }

    private function importPage(int $page): void
    {
        echo "page: {$page} \n";

        $url = $this->baseUrl.'/search';
        $params = [
            'api-key' => $this->apiKey,
            'page-size' => $this->perPage,
            'from-date' => $this->fromDate,
            'to-date' => $this->toDate,
            'page' => $page,
        ];

        $response = Arr::get(
            Http::get($url, $params)->json(),
            'response',
            []
        );

        $results = Arr::get($response, 'results', []);

        foreach ($results as $articleData) {
            $hash = $this->createHashIfNotExists($articleData['id']);

            if (! $hash) {
                continue;
            }

            $article = $this->importArticle($hash, $articleData);
            $this->checkAndAttachCategory($articleData['pillarName'] ?? '', $article->id);
        }

        if ($this->hasNextPage(Arr::get($response, 'pages'), $page)) {
            $this->importPage(++$page);
        }
    }

    private function importArticle(string $hash, array $data): Article
    {
        return $this->source->articles()->create([
            'hash' => $hash,
            'title' => $data['webTitle'],
            'url' => $data['webUrl'],
            'published_at' => now(), // TODO: this should be null => later admins publish them + we must have a global scope on this...
        ]);
    }

    private function checkAndAttachCategory(string $title, int $articleId): void
    {
        if (empty($title)) {
            return;
        }

        $category = Category::firstOrCreate([
            'title' => $title,
        ]);

        $category->articles()->attach($articleId);
    }

    private function hasNextPage(int $pages, int $currentPage): bool
    {
        return $pages > $currentPage;
    }
}
