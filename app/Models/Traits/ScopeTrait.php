<?php

namespace App\Models\Traits;

trait ScopeTrait
{
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeEnableSearch($query, $value)
    {
        return $query->where('enable', $value);
    }

    public function scopeColumnLikeSearch($query, $column, $value)
    {
        return $query->where($column, 'like', $value . '%');
    }

    public function scopeColumnEqualSearch($query, $column, $value)
    {
        return $query->where($column, $value);
    }

    public function scopeColumnInSearch($query, $column, array $value)
    {
        return $query->whereIn($column, $value);
    }
}
