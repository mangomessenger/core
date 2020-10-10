<?php

namespace App\Http\Requests\Chat\Channel;

use App\ConfigurationManager;
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
            'title' => config('rules.channels.title'),
            'description' => config('rules.channels.description'),
            'tag' => array_merge(config('rules.channels.tag'),
                [
                    Rule::unique('channels')
                        ->ignore(Channel::find($this->route('channel'))->id, 'id')
                ]),
            'photo' => config('rules.channels.photo'),
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
