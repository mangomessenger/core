<?php

namespace App\Http\Requests\Auth;

use App\ConfigurationManager;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
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
            'refresh_token' => config('rules.auth.refresh_token'),
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
            '*.required' => 'The :attribute field is required.',
            '*.min' => ':Attribute minimum length is :min.',
            '*.max' => ':Attribute maximum length is :max.',
            '*.string' => ':Attribute field must be a string.',
        ];
    }
}
