<?php

namespace Winata\PackageBased\Abstracts;

use Illuminate\Support\Facades\DB;

/**
 * BaseAction
 *
 * This abstract class defines the structure for all "Action" classes
 * that require input validation and business logic encapsulation.
 * It provides optional support for wrapping the entire process in a
 * database transaction.
 */
abstract class BaseAction
{
    /**
     * @param bool $usingDBTransaction
     *        If set to true, the execute() method will run inside a database transaction.
     */
    public function __construct(
        public bool $usingDBTransaction = false
    ) {}

    /**
     * Define the input validation rules and perform validation.
     *
     * Should be implemented by child classes to define the required
     * validation logic. This is executed before handle().
     *
     * @return static
     */
    public abstract function rules(): self;

    /**
     * Main handler for the action.
     *
     * Should be implemented by child classes to perform the
     * core logic of the action.
     *
     * @return mixed
     */
    public abstract function handle(): mixed;

    /**
     * Execute the rules and handle logic safely.
     *
     * This method first validates the inputs by calling rules(),
     * and then calls the handle() method to perform the core logic.
     * If $usingDBTransaction is true, the entire execution is wrapped
     * inside a DB transaction to ensure atomicity.
     *
     * This means:
     * - If any exception occurs, the transaction is rolled back.
     * - If successful, changes are committed.
     *
     * @return mixed The result from the handle() method.
     * @throws \Throwable
     */
    public function execute(): mixed
    {
        $this->rules();

        if ($this->usingDBTransaction) {
            return DB::transaction(fn () => $this->handle());
        }

        return $this->handle();
    }

    /**
     * A convenience method for executing rules and handle without a transaction.
     *
     * Useful when you don't need to wrap the process in a DB transaction,
     * but still want to ensure rules are validated before execution.
     *
     * @return mixed The result from the handle() method.
     */
    public function validateAndHandle(): mixed
    {
        $this->rules();
        return $this->handle();
    }
}
