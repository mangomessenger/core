<?php

namespace App\Http\Requests\Message;

use App\ConfigurationManager;
use App\Models\Message;
use App\Http\Requests\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $chat = Message::find($this->route('message'));

        return $chat && $this->user()->can('update', $chat);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => config('rules.messages.message'),
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
        ];
    }
}
