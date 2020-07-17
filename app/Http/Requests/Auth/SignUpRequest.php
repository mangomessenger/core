<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Exceptions\PhoneCountryCodeEmptyException;
use App\Exceptions\PhoneNumberEmptyException;
use App\Rules\Auth\PhoneCodeValid;
use App\Rules\Auth\UnoccupiedPhone;
use App\Rules\Auth\PhoneCodeHashValid;
use App\Services\Auth\AuthRequestService;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param AuthRequestService $authRequestService
     * @return array
     * @throws AuthRequestExpiredException
     * @throws PhoneNumberEmptyException
     * @throws PhoneCountryCodeEmptyException
     */
    public function rules(AuthRequestService $authRequestService)
    {
        if (is_null($this->phone_number)) throw new PhoneNumberEmptyException();
        if (is_null($this->country_code)) throw new PhoneCountryCodeEmptyException();

        $authRequest = $authRequestService->findByPhone($this->phone_number, $this->country_code);
        if (is_null($authRequest)) throw new AuthRequestExpiredException();

        return [
            'phone_number' => [
                'required',
                'phone:country_code',
                new UnoccupiedPhone($this->country_code),
            ],
            'country_code' => 'required_with:phone',
            'name' => 'required|max:100',
            'phone_code_hash' => [
                'required',
                'max:255',
                new PhoneCodeHashValid($authRequest),
            ],
            'phone_code' => [
                'required',
                'digits:5',
                new PhoneCodeValid($authRequest),
            ],
            'terms_of_service_accepted' => [
                'required',
                'accepted',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone' => 'The :attribute field contains an invalid phone number.',
            '*.required' => 'The :attribute field is required',
            'phone_code.digits' => ':Attribute must have an exact length of :digits.',
            'phone_code_hash.max' => ':Attribute maximum length is :max',
            'terms_of_service_accepted.accepted' => 'The :attribute must be accepted.'
        ];
    }
}
