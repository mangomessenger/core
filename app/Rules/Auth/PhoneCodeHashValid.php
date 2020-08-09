<?php

namespace App\Rules\Auth;

use App\Models\AuthRequest;
use Illuminate\Contracts\Validation\Rule;

class PhoneCodeHashValid implements Rule
{
    private AuthRequest $authRequest;

    /**
     * Create a new rule instance.
     *
     * @param AuthRequest $authRequest
     */
    public function __construct(AuthRequest $authRequest)
    {
        $this->authRequest = $authRequest;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->authRequest->phone_code_hash === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':Attribute is invalid.';
    }
}
