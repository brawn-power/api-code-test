<?php

namespace App\Repositories;
use App\Contracts\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    const DEFAULT_ORDER_BY = 'created_at';
    const DEFAULT_SORT_BY = 'desc';
    const DEFAULT_PAGE_SIZE = 15;

    public function order($query, $orderBy = self::DEFAULT_ORDER_BY, $sortBy = self::DEFAULT_SORT_BY) {
        return $query->orderBy($orderBy, $sortBy);
    }

    public function paginate($query, $attributes) {
        return $query->paginate($attributes['page_size'] ?? self::DEFAULT_PAGE_SIZE);
    }
}
