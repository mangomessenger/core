<?php

namespace App\Services\Auth;

use App\Models\AuthRequest;
use App\Services\ModelService;
use Propaganistas\LaravelPhone\PhoneNumber;

class AuthRequestService extends ModelService
{
    protected AuthRequest $model;

    /**
     * AuthRequestService constructor.
     *
     * @param AuthRequest $error
     */
    public function __construct(AuthRequest $error)
    {
        $this->model = $error;
    }

    /**
     * @param string|null $phoneNumber
     * @param string|null $countryCode
     * @return AuthRequest|null
     */
    public function findByPhone(?string $phoneNumber, ?string $countryCode): ?AuthRequest
    {
        return $this->model
            ->where('phone_number', PhoneNumber::make($phoneNumber, $countryCode)
                ->formatE164())
            ->first();
    }

    /**
     * @param string $phoneNumber
     * @param string $countryCode
     * @return bool
     */
    public function existsByPhone(string $phoneNumber, string $countryCode): bool
    {
        return $this->model
            ->where('phone_number', PhoneNumber::make($phoneNumber, $countryCode)
                ->formatE164())
            ->exists();
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        $input['phone_number'] = PhoneNumber::make($input['phone_number'], $input['country_code'])->formatE164();

        return $this->model->create($input);
    }
}
