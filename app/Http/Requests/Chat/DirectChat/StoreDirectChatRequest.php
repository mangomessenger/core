<?php

namespace App\Http\Requests\Chat\DirectChat;

use Illuminate\Foundation\Http\FormRequest;

class StoreDirectChatRequest extends FormRequest
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
            'username' => 'required|exists:users,username'
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
            '*.integer' => 'The :attribute field must be an integer.',
            'username.exists' => 'Username is not valid.',
        ];
    }
}
