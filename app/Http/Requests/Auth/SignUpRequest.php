<?php

namespace App\Http\Requests\Auth;

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
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number' => [
                'required',
                'phone:country_code',
            ],
            'country_code' => 'required_with:phone',
            'name' => 'required|max:100',
            'phone_code_hash' => [
                'required',
                'max:255',
            ],
            'phone_code' => [
                'required',
                'digits:5',
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
            '*.required' => 'The :attribute field is required.',
            'phone_code.digits' => ':Attribute must have an exact length of :digits.',
            'phone_code_hash.max' => ':Attribute maximum length is :max.',
            'terms_of_service_accepted.accepted' => 'The :attribute must be accepted.',
            'numeric' => ':Attribute should not contain any characters.'
        ];
    }
}
