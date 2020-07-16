<?php

namespace App\Rules\Auth;

use App\Exceptions\Auth\PhoneNumberUnoccupiedException;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class OccupiedPhone implements Rule
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
     * @throws PhoneNumberUnoccupiedException
     */
    public function passes($attribute, $value)
    {
        if (!User::where('phone_number', $value)
            ->where('country_code', $this->countryCode)->exists()) {
            throw new PhoneNumberUnoccupiedException();
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
