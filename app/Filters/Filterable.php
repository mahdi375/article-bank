<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilters(Builder $builder, $params)
    {
        $className = class_basename($this);
        $filterClass = 'App\\Filters\\'.$className.'Filters';

        return (new $filterClass($builder))->apply($params);
    }
}
