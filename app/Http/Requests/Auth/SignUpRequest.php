<?php

namespace App\Http\Requests\Auth;

use App\ConfigurationManager;
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
            'phone_number' => config('rules.users.phone_number'),
            'country_code' => config('rules.users.country_code'),
            'name' => array_merge(config('rules.users.name'), ['required']),
            'username' => array_merge(config('rules.users.username'),
                ['unique:users,username']
            ),
            'phone_code_hash' => config('rules.auth.phone_code_hash'),
            'phone_code' => config('rules.auth.phone_code'),
            'terms_of_service_accepted' => config('rules.auth.terms_of_service_accepted'),
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
            'numeric' => ':Attribute should not contain any characters.',
            '*.string' => ':Attribute field must be a string.',
        ];
    }
}
