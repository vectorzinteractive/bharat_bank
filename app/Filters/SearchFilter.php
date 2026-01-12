<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    protected array $columns;

    public function __construct(array $columns = [])
    {
        $this->columns = $columns;
    }

    public function __invoke(Builder $query, $value, string $property)
    {
        if (empty($this->columns)) {
            return $query;
        }

        $query->where(function ($q) use ($value) {
            foreach ($this->columns as $column) {
                $q->orWhere($column, 'LIKE', "%{$value}%");
            }
        });

        return $query;
    }
}
