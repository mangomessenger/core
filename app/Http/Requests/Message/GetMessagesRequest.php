<?php

namespace App\Http\Requests\Message;

use App\Chat;
use Illuminate\Foundation\Http\FormRequest;

class GetMessagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (isset($this->chat_id)) {
            return auth()->user()->can('view', Chat::find($this->chat_id));
        }
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
            'chat_id' => 'required|exists:chats,id',
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
            '*.exists' => ':Attribute is invalid.',
        ];
    }
}
