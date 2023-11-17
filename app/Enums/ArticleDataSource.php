<?php

namespace App\Enums;

use App\ArticleProviders\ArticleProvider;
use App\ArticleProviders\Guardian;
use App\ArticleProviders\NewsApi;
use App\ArticleProviders\NewYorkTimes;
use App\Exceptions\ArticleDataSourceException;

enum ArticleDataSource: string
{
    case GUARDIAN = 'Guardian';
    case NEW_YORK_TIMES = 'New-York-Times';
    case NEWS_API = 'News-Api';

    public function provider(): ArticleProvider
    {
        return match ($this) {
            self::GUARDIAN => new Guardian(),
            self::NEW_YORK_TIMES => new NewYorkTimes(),
            self::NEWS_API => new NewsApi(),

            default => throw ArticleDataSourceException::invalidDataSource(),
        };
    }
}
