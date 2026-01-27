<?php
// namespace App\Traits;

// trait Filterable
// {
//     public function scopeFilterByParams($query, $request, array $filterMap = [])
//     {
//         if (!$request->all()) return $query;

//         foreach ($filterMap as $param => $config) {
//             $column   = $config['column'] ?? $param;
//             $relation = $config['relation'] ?? null;
//             $operator = $config['operator'] ?? '=';

//             if (!$request->filled($param)) continue;

//             $values = is_array($request->{$param}) ? $request->{$param} : [$request->{$param}];

//             if ($relation) {
//                 $query->whereHas($relation, fn($q) => $q->whereIn($column, $values));
//             } else {
//                 if (in_array($operator, ['>=', '<='])) {
//                     $query->where($column, $operator, $values[0]);
//                 } elseif ($operator === 'like') {
//                     foreach ($values as $v) $query->where($column, 'like', "%{$v}%");
//                 } else {
//                     $query->whereIn($column, $values);
//                 }
//             }
//         }

//         return $query;
//     }
// }
namespace App\Traits;

trait Filterable
{
    public function scopeFilterByParams($query, $request, array $filterMap = [])
    {
        if (!$request->all()) return $query;

        foreach ($filterMap as $param => $config) {
            $operator = $config['operator'] ?? '=';
            if (!$request->filled($param)) continue;

            $values = is_array($request->{$param}) ? $request->{$param} : [$request->{$param}];

            if (isset($config['columns']) && is_array($config['columns'])) {
                $query->where(function ($q) use ($values, $config) {
                    foreach ($values as $v) {
                        foreach ($config['columns'] as $colConfig) {
                            $col       = $colConfig['column'];
                            $relation  = $colConfig['relation'] ?? null;
                            $operator  = $colConfig['operator'] ?? 'like';

                            if ($relation) {
                                $q->orWhereHas($relation, fn($q2) => $q2->where($col, $operator, $operator === 'like' ? "%{$v}%" : $v));
                            } else {
                                $q->orWhere($col, $operator, $operator === 'like' ? "%{$v}%" : $v);
                            }
                        }
                    }
                });
            }
            else {
                $column   = $config['column'] ?? $param;
                $relation = $config['relation'] ?? null;

                if ($relation) {
                    $query->whereHas($relation, fn($q) => $q->whereIn($column, $values));
                } else {
                    if (in_array($operator, ['>=', '<='])) {
                        $query->where($column, $operator, $values[0]);
                    } elseif ($operator === 'like') {
                        foreach ($values as $v) $query->where($column, 'like', "%{$v}%");
                    } else {
                        $query->whereIn($column, $values);
                    }
                }
            }
        }

        return $query;
    }
}
