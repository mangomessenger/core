<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends FormRequest
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
            'message' => 'required|max:300',
            'peer' => 'required|array',
            'peer.destination_id' => 'required|integer',
            'peer.chat_type' => 'required'
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
            '*.array' => ':Attribute should be a type of array.',
            'peer.destination_id.required' => 'Destination id is required.',
            'peer.destination_id.integer' => 'Destination id should be a type of int.',
            'peer.chat_type.required' => 'ChatFacade type is required.',
        ];
    }
}
