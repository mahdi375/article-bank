<?php

namespace App\ArticleProviders;

use App\Exceptions\NewYorkTimesException;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewYorkTimes extends ArticleProvider
{
    private string $baseUrl;

    private string $apiKey;

    private string $date;

    private Source $source;

    public function import(): void
    {
        $this->initConfigs();
        $this->initDate();
        $this->setSource();

        $this->importPage(1);
    }

    private function initConfigs(): void
    {
        $this->baseUrl = config('services.new-york-times.base-url');
        $this->apiKey = config('services.new-york-times.api-key');

        if ((! $this->baseUrl) || (! $this->apiKey)) {
            throw NewYorkTimesException::invalidConfigs();
        }
    }

    private function initDate(): void
    {
        $this->date = date('Y-m-d', now()->subDay()->timestamp);
    }

    private function setSource()
    {
        if (! ($source = Source::where('name', 'NewYorkTimes')->first())) {
            throw NewYorkTimesException::sourceNotFound();
        }

        $this->source = $source;
    }

    private function importPage(int $page)
    {
        echo "page: {$page} \n";

        $url = $this->baseUrl.'/svc/search/v2/articlesearch.json';
        $params = [
            'api-key' => $this->apiKey,
            'fq' => "pub_date:({$this->date})",
            'page' => $page,
        ];

        $response = Arr::get(
            Http::get($url, $params)->json(),
            'response',
            []
        );

        $docs = Arr::get($response, 'docs', []);

        foreach ($docs as $articleData) {
            $hash = $this->createHashIfNotExists($articleData['_id']);

            if (! $hash) {
                continue;
            }

            $article = $this->importArticle($hash, $articleData);
            $this->checkAndAttachCategory($articleData['news_desk'] ?? '', $article->id);
            $this->importAuthors($articleData['byline']['person'] ?? [], $article->id);
        }

        if ($this->hasNextPage(Arr::get($response, 'meta.hits', 0), $page)) {
            sleep(13); // to  avoid hitting the nyt rate limit (https://developer.nytimes.com/faq) question 11
            $this->importPage(++$page);
        }
    }

    private function importArticle(string $hash, array $data): Article
    {
        return $this->source->articles()->create([
            'hash' => $hash,
            'title' => Str::limit($data['abstract'], 250),
            'description' => $data['lead_paragraph'],
            'url' => $data['web_url'],
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

    private function importAuthors(array $authorsData, int $articleId): void
    {
        foreach ($authorsData as $data) {
            $name = ($data['firstname'] ?? '').' '.($data['lastname'] ?? '');

            if (empty(trim($name))) {
                continue;
            }

            $author = Author::firstOrCreate([
                'name' => $name,
            ]);

            $author->articles()->attach($articleId);
        }
    }

    private function hasNextPage(int $all, int $currentPage): bool
    {
        return $all > ($currentPage * 10);
    }
}
