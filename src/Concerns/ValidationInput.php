<?php

namespace Winata\PackageBased\Concerns;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Container\Container;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

/**
 * Trait ValidationInput
 *
 * Provides a reusable validation trait that stores validated data
 * and supports both API and web-based form submissions.
 *
 * @package Winata\PackageBased\Abstracts
 */
trait ValidationInput
{
    /**
     * Holds the validated input data.
     *
     * @var array
     */
    protected array $validatedData = [];

    /**
     * Validates the given input data using the provided rules.
     * If the request is not JSON, it may return a redirect response on failure.
     *
     * @param Request|array $inputs The data to be validated.
     * @param array $rules The validation rules.
     * @param array $messages Custom error messages (optional).
     * @param array $attributes Custom attribute names (optional).
     *
     * @return array|RedirectResponse The validated data or redirect on failure.
     *
     * @throws AuthorizationException
     * @throws ValidationException If validation fails in a JSON context.
     */
    public function validate(
        Request|array $inputs,
        array $rules,
        array $messages = [],
        array $attributes = []
    ): array|RedirectResponse {
        if ($inputs instanceof Request) {
            if (!$inputs->authorize())
                throw new AuthorizationException("You are unauthorized to access this resource");
            $validator = Validator::make($inputs->input(), $inputs->rules(), $inputs->messages());

        } else {
            $validator = Validator::make($inputs, $rules, $messages, $attributes);

        }
        $validatedData = $validator->validated();
        if (!request()->expectsJson() && $validator->fails() && !config('winata.response.force_to_json')) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->setValidatedData($validatedData);

        return $validatedData;
    }

    /**
     * Set the validated data internally.
     *
     * @param array $validatedData
     * @return $this
     */
    protected function setValidatedData(array $validatedData): self
    {
        $this->validatedData = $validatedData;

        return $this;
    }

    /**
     * Get the previously validated data.
     *
     * @return array
     */
    protected function getValidatedData(): array
    {
        return $this->validatedData;
    }
}
