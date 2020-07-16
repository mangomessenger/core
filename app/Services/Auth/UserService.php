<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Services\ModelService;
use App\User;

class UserService extends ModelService
{
    protected User $model;

    /**
     * AuthRequestService constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findByPhone(string $phoneNumber, string $countryCode): ?User
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
