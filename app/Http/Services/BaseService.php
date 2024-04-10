<?php

namespace App\Http\Services;
use App\Models\WorkoutSession;
use App\Traits\CommonTrait;

class BaseService
{
    const DEFAULT_ORDER_BY = 'created_at';
    const DEFAULT_SORT_BY = 'desc';
    const DEFAULT_PAGE_SIZE = 15;

    public function order($query, $orderBy = self::DEFAULT_ORDER_BY, $sortBy = self::DEFAULT_SORT_BY) {
        return $query->orderBy($orderBy, $sortBy);
    }

    public function paginate($query, $request) {
        return $query->paginate($request['page_size'] ?? self::DEFAULT_PAGE_SIZE);
    }
}
