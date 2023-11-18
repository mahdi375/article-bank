<?php

namespace App\Filters;

class ArticleFilters extends AbstractFilters
{
    // TODO: we can use advanced searchs like `scout`... (bucause `%like%` ignore mysql index)
    public function search(string $value): void
    {
        $this->builder->where('title', 'LIKE', "%$value%");
    }

    public function sources(string $value): void
    {
        $sources = explode(',', $value);

        $this->builder->whereHas('source', function ($query) use ($sources) {
            $query->whereIn('sources.name', $sources);
        });
    }

    public function publishedAt(string $value): void
    {
        $this->builder->whereDate(
            'published_at',
            $this->castStringToCarbon($value)
        );
    }

    public function categories(string $value): void
    {
        $categories = explode(',', $value);

        $this->builder->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('categories.title', $categories);
        });
    }

    public function authors(string $value): void
    {
        $authors = explode(',', $value);

        $this->builder->whereHas('authors', function ($query) use ($authors) {
            $query->whereIn('authors.name', $authors);
        });
    }
}
