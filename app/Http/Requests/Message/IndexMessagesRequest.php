<?php

namespace App\Http\Requests\Message;

use App\Facades\Chat;
use Illuminate\Foundation\Http\FormRequest;

class IndexMessagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $chat = Chat::chats()->findChat(
            $this->chat_type,
            $this->chat_id
        );

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
            'chat_id' => "required",
            'chat_type' => 'required|exists:chat_types,name',
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
            '*.exists' => ':Attribute is invalid.',
            '*.string' => ':Attribute field must be a string.',
        ];
    }
}
