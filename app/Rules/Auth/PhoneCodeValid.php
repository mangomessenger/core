<?php

namespace App\Rules\Auth;

use App\AuthRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PhoneCodeValid implements Rule
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
        return Hash::check($value, $this->authRequest->phone_code_hash);
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
