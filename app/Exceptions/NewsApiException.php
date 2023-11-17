<?php

namespace App\Exceptions;

use Exception;

class NewsApiException extends Exception
{
    public static function invalidConfigs(): self
    {
        return new self('Invalid NewsApi configs! Please check NewsApi required envs.');
    }

    public static function sourceNotFound(): self
    {
        return new self('NewsApi source not found! Make sure you run the seeds.');
    }
}
