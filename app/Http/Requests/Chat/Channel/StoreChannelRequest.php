<?php

namespace App\Http\Requests\Chat\Channel;

use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
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
            'usernames' => 'array',
            'usernames.*' => 'exists:users,username',
            'title' => 'required',
            'tag' => '',
            'photo' => 'image',
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
            '*.image' => 'The :attribute field  must be an image.',
            '*.array' => 'The :attribute field must be an array.',
            'usernames.*.exists' => 'Username is not valid.'
        ];
    }
}
