<?php

namespace App\Exceptions;

use Exception;

class ArticleDataSourceException extends Exception
{
    public static function invalidDataSource(): self
    {
        return new self('Invalid article data source!');
    }
}
