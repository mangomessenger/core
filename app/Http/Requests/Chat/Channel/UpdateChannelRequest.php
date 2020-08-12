<?php

namespace App\Http\Requests\Chat\Channel;

use App\Models\Channel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $chat = Channel::find($this->route('channel'));

        return $chat && $this->user()->can('access', $chat);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|min:1|max:100',
            'description' => 'max:255',
            'tag' => [
                'nullable',
                'string',
                'regex:/(^[A-Za-z0-9]+$)+/',
                'min:3',
                'max:20',
                Rule::unique('channels')->ignore(Channel::find($this->route('channel'))->id, 'id')
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
            '*.required' => 'The :attribute field is required.',
            '*.max' => ':Attribute maximum length is :max.',
            '*.min' => ':Attribute minimum length is :min.',
            '*.string' => ':Attribute field must be a string.',
            '*.regex' => ':Attribute does contain forbidden symbols.',
            '*.unique' => ':Attribute is already occupied.',
        ];
    }
}
