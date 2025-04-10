<?php

namespace Winata\PackageBased\Abstracts;

use Illuminate\Support\Facades\DB;

/**
 * Class BaseAction
 *
 * Abstract base class for action-based services that encapsulate
 * business logic and optionally run within a database transaction.
 *
 * To use this, extend the class and implement:
 * - rules(): to define input validation
 * - handle(): to implement core logic
 *
 * @package Winata\PackageBased\Abstracts
 */
abstract class BaseAction
{
    /**
     * Indicates whether the action should run inside a database transaction.
     *
     * @var bool
     */
    public bool $usingDBTransaction;

    /**
     * Create a new BaseAction instance.
     *
     * @param bool $usingDBTransaction
     */
    public function __construct(bool $usingDBTransaction = false)
    {
        $this->usingDBTransaction = $usingDBTransaction;
    }

    /**
     * Define validation rules and perform validation.
     *
     * This should be implemented in child classes to handle any
     * pre-execution validation logic.
     *
     * @return $this
     */
    abstract public function rules(): self;

    /**
     * Execute the core logic of the action.
     *
     * This should be implemented in child classes and contains
     * the actual logic for the action.
     *
     * @return mixed
     */
    abstract public function handle(): mixed;

    /**
     * Execute the action.
     *
     * This method calls `rules()` followed by `handle()`.
     * If `$usingDBTransaction` is true, the execution is wrapped
     * within a database transaction.
     *
     * @return mixed
     *
     * @throws \Throwable
     */
    public function execute(): mixed
    {
        $this->rules();

        return $this->usingDBTransaction
            ? DB::transaction(fn () => $this->handle())
            : $this->handle();
    }

    /**
     * Convenience method to run `rules()` and then `handle()` without transaction.
     *
     * Useful when you don't want a transaction but still want validation.
     *
     * @return mixed
     */
    public function validateAndHandle(): mixed
    {
        return $this->rules()->handle();
    }
}
