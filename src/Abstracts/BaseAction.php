<?php

namespace Winata\PackageBased\Abstracts;

use Illuminate\Support\Facades\DB;

abstract class BaseAction
{
    public function __construct(
        public bool $usingDBTransaction = false
    )
    {
        $rules = $this->rules();

        if ($this->usingDBTransaction){
            DB::beginTransaction();
        }

        return $rules;
    }

    public abstract function rules(): self;

    public abstract function handle(): mixed;

    public function __destruct()
    {
        if ($this->usingDBTransaction){
            DB::commit();
        }
    }
}
