<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Services\ModelService;

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
        return $this->model->where('phone_number', $phoneNumber)
            ->where('country_code', $countryCode)
            ->first();
    }

    /**
     * @param string $phoneNumber
     * @param string $countryCode
     * @return bool
     */
    public function existsByPhone(string $phoneNumber, string $countryCode): bool
    {
        return $this->model->where('phone_number', $phoneNumber)
            ->where('country_code', $countryCode)
            ->exists();
    }
}
