<?php

namespace App\Contracts;


interface WorkoutSessionRepositoryInterface
{
  public function filter(array $attributes);
}