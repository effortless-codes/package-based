<?php

namespace Winata\PackageBased\Database\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model as LaravelModel;

class Model extends LaravelModel
{

    /**
     * @param array $data
     *
     * @return array
     */
    public static function getFillableAttribute(array $data): array
    {
        $fillable = (new static)->getFillable();

        return Arr::only($data, Arr::flatten($fillable));
    }

}
