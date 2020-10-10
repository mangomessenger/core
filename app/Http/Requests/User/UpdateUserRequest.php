<?php

namespace App\Http\Requests\User;

use App\ConfigurationManager;
use App\Http\Requests\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = User::find($this->route('user_id'));

        return $user && $user->is($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => config('rules.users.name'),
            'bio' => config('rules.users.bio'),
            'username' => array_merge(config('rules.users.username'),
                [Rule::unique('users')->ignore($this->user()->id, 'id')]
            ),
            'photo' => config('rules.users.photo'),
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
