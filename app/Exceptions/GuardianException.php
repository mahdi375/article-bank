<?php

namespace App\Exceptions;

use Exception;

class GuardianException extends Exception
{
    public static function invalidConfigs(): self
    {
        return new self('Invalid Guardian configs! Please check guardian required envs.');
    }

    public static function sourceNotFound(): self
    {
        return new self('Guardian source not found! Make sure you run the seeds.');
    }
}
