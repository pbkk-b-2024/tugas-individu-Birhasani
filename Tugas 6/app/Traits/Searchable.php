<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Trait Searchable
 * Provides searching and filtering functionalities.
 */
trait Searchable
{
    protected function scopePaginator(Builder $query, Request $request, $limit = 10)
    {
        $limit = $request->input('limit', $limit);
        return $query->paginate($limit);
    }

    protected function relationExists(string $relation): bool
    {
        return method_exists($this, $relation);
    }

    protected function scopeSearch(Builder $query, string $search, $ignoreTimestamps = true): Builder
    {
        $columns = Schema::getColumnListing($this->getTable());
        if ($ignoreTimestamps) {
            $columns = array_diff($columns, ['created_at', 'updated_at']);
        }
        $table = $this->getTable();
        $query->where(function ($q) use ($search, $columns, $table) {
            foreach ($columns as $column) {
                $q->orWhere("{$table}"."."."{$column}", 'like', "%{$search}%");
            }
        });
        return $query;
    }

    protected function scopeWithRelation(Builder $query, string $relation): Builder
    {
        if($this->relationExists($relation)) {
            return $query->with($relation);
        } else {
            return $query;
        }
    }

    protected function scopeSearchRelation(Builder $query, string $search, string $relation ,array $relatedColumns): Builder
    {
        if($this->relationExists($relation)) {
            $query->orWhereHas($relation, function ($q) use ($search, $relatedColumns) {
                foreach ($relatedColumns as $relatedColumn) {
                    $q->where("{$relatedColumn}", 'like', '%' . $search . '%');
                }
            });
            return $query;
        } else {
            return $query;
        }
    }

    protected function scopeFilter(Builder $query, string $search, string $column = 'id'): Builder 
    {
        if(Schema::hasColumn($this->getTable(), $column)) {
            return $query->where($column, 'like', '%' . $search . '%');
        } else {
            throw new \Exception('Column ' . $column . ' does not exist in table ' . $this->getTable());
        }
    }

    protected function scopeFilters(Builder $query, string $search,array $columns = ['id']): Builder
    {
        foreach ($columns as $column) {
            $query = $this->scopeFilter($query, $search, $column);
        }
        return $query;
    }

    protected function scopeFilterByRelation(Builder $query, string $search, string $relation = '', $column = 'id') : Builder
    {
        if ($relation && $this->relationExists($relation)) {
            return $query->whereHas($relation, function ($q) use ($search, $column) {
                $q->where($column, 'like', '%' . $search . '%');
            });
        }
    }


}
