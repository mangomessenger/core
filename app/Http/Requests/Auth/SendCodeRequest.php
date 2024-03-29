<?php

namespace App\Http\Requests\Auth;

use App\ConfigurationManager;
use Illuminate\Foundation\Http\FormRequest;

class SendCodeRequest extends FormRequest
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
            'fingerprint' => config('rules.auth.fingerprint'),
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
            '*.min' => ':Attribute minimum length is :min.',
            '*.max' => ':Attribute maximum length is :max.',
            'numeric' => ':Attribute should not contain any characters.',
            '*.string' => ':Attribute field must be a string.',
        ];
    }
}
