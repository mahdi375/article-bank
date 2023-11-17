<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

abstract class AbstractFilters
{
    public function __construct(public Builder $builder)
    {
    }

    public function apply($params): void
    {
        foreach ($params as $methodName => $value) {
            $methodCamelCaseName = Str::camel($methodName);

            if (is_null($value) || ! method_exists($this, $methodCamelCaseName)) {
                continue;
            }

            $this->$methodCamelCaseName($value);
        }
    }

    protected function castStringToCarbon(string $value, string $format = 'Y-m-d'): Carbon
    {
        return Carbon::createFromFormat($format, $value);
    }

    protected function castStringToBool(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
