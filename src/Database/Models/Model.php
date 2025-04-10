<?php

namespace Winata\PackageBased\Database\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Support\Arr;

/**
 * Class Model
 *
 * Base model with helper for extracting only fillable attributes from a given array.
 *
 * @package Winata\PackageBased\Abstracts
 */
class Model extends LaravelModel
{
    /**
     * Extract only the fillable attributes from the given data.
     *
     * @param array $data The raw input data.
     *
     * @return array The filtered array containing only fillable keys.
     */
    public static function getFillableAttribute(array $data): array
    {
        $fillable = (new static())->getFillable();

        return Arr::only($data, Arr::flatten($fillable));
    }
}
