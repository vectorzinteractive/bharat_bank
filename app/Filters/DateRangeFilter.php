<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DateRangeFilter implements Filter
{
    protected string $column;

    public function __construct(string $column = 'created_at')
    {
        $this->column = $column;
    }

    public function __invoke(Builder $query, $value, string $property)
{
    if (is_array($value)) {
        $value = implode(',', $value); // fallback if somehow array passed
    }

    $dates = explode(',', $value);

    if (count($dates) !== 2) {
        return $query;
    }

    $from = Carbon::parse($dates[0])->startOfDay();
    $to   = Carbon::parse($dates[1])->endOfDay();

    return $query->whereBetween($this->column, [$from, $to]);
}


    // public function __invoke(Builder $query, $value, string $property)
    // {
    //     // Expected format: 2024-01-01,2024-01-31
    //     $dates = explode(',', $value);

    //     if (count($dates) !== 2) {
    //         return $query;
    //     }

    //     $from = Carbon::parse($dates[0])->startOfDay();
    //     $to   = Carbon::parse($dates[1])->endOfDay();

    //     return $query->whereBetween($this->column, [$from, $to]);
    // }
}
