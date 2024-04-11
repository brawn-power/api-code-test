<?php

namespace App\Contracts;


interface BaseRepositoryInterface
{
  public function order($query, string $orderBy, string $sortBy);
  public function paginate($query, array $attributes);
}