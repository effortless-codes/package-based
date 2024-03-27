<?php

namespace Winata\PackageBased\Abstracts;

abstract class BaseAction
{
    public function __construct()
    {
        return $this->rules();
    }

    public abstract function rules(): self;

    public abstract function handle(): mixed;
}
