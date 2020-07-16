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

    public function findByPhone(string $phoneNumber, string $countryCode): ?AuthRequest
    {
        return $this->model->where('phone_number', $phoneNumber)
            ->where('country_code', $countryCode)
            ->first();
    }

    public function existsByPhone(string $phoneNumber, string $countryCode): bool
    {
        return $this->model->where('phone_number', $phoneNumber)
            ->where('country_code', $countryCode)
            ->exists();
    }
}
