<?php

namespace App\Exceptions;

use Exception;

class NewYorkTimesException extends Exception
{
    public static function invalidConfigs(): self
    {
        return new self('Invalid NewYorkTimes configs! Please check NewYorkTimes required envs.');
    }

    public static function sourceNotFound(): self
    {
        return new self('NewYorkTimes source not found! Make sure you run the seeds.');
    }
}
