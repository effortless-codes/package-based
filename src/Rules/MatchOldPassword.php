<?php

namespace Winata\PackageBased\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

/**
 * Class MatchOldPassword
 *
 * Custom validation rule to verify that the provided password
 * matches the specified user's current password.
 *
 * This rule is typically used to validate if the provided "old_password"
 * field matches the authenticated user's password before allowing an update.
 *
 * @package Winata\PackageBased\Rules
 */
class MatchOldPassword implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param  \App\Models\User  $user  The user whose password will be checked.
     */
    public function __construct(
        public readonly User $user
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute The name of the attribute under validation.
     * @param  mixed   $value     The value of the attribute under validation.
     * @param  \Closure(string): void  $fail The callback that should be called on failure.
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->user instanceof User || ! Hash::check($value, $this->user->password)) {
            $fail(__('The old password is incorrect.'));
        }
    }
}
