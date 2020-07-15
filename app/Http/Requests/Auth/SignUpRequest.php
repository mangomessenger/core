<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Rules\Auth\AuthRequestExists;
use App\Rules\Auth\PhoneCodeValid;
use App\Rules\Auth\UnoccupiedPhone;
use App\Rules\Auth\PhoneCodeHashValid;
use App\Services\Auth\AuthRequestService;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
     * @param AuthRequestService $authRequestService
     * @return array
     * @throws AuthRequestExpiredException
     */
    public function rules(AuthRequestService $authRequestService)
    {
        $authRequest = $authRequestService->findByPhone($this->phone_number, $this->country_code);
        if (is_null($authRequest)) throw new AuthRequestExpiredException();

        return [
            'phone_number' => [
                'required',
                'phone:country_code',
                new UnoccupiedPhone($this->country_code),
            ],
            'country_code' => 'required_with:phone',
            'name' => 'required|max:100',
            'phone_code_hash' => [
                'required',
                'max:255',
                new PhoneCodeHashValid($authRequest),
            ],
            'phone_code' => [
                'required',
                'digits:5',
                new PhoneCodeValid($authRequest),
            ],
            'terms_of_service_accepted' => [
                'required',
                'accepted',
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
            'phone' => 'The :attribute field contains an invalid phone number.',
        ];
    }
}
