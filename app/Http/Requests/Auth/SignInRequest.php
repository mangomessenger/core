<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Exceptions\PhoneCountryCodeEmptyException;
use App\Exceptions\PhoneNumberEmptyException;
use App\Rules\Auth\OccupiedPhone;
use App\Rules\Auth\PhoneCodeValid;
use App\Rules\Auth\PhoneCodeHashValid;
use App\Services\Auth\AuthRequestService;
use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
        return [
            'phone_number' => [
                'required',
                'phone:country_code',
            ],
            'country_code' => 'required_with:phone',
            'phone_code_hash' => [
                'required',
                'max:255',
            ],
            'phone_code' => [
                'required',
                'digits:5',
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
            '*.required' => 'The :attribute field is required.',
            'phone_code.digits' => ':Attribute must have an exact length of :digits.',
            'phone_code_hash.max' => ':Attribute maximum length is :max.',
        ];
    }
}
