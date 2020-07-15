<?php

namespace App\Rules\Auth;

use App\Exceptions\Auth\PhoneNumberOccupiedException;
use Illuminate\Contracts\Validation\Rule;

class UnoccupiedPhone implements Rule
{
    private string $countryCode;

    /**
     * Create a new rule instance.
     *
     * @param string $countryCode
     */
    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
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
        if (!is_null(\App\User::where('phone_number', $value)
            ->where('country_code', $this->countryCode)->first())) {
            throw new PhoneNumberOccupiedException();
        } else return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
