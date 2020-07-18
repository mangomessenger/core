<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Services\ModelService;
use App\User;
use Propaganistas\LaravelPhone\PhoneNumber;

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

    /**
     * @param string|null $phoneNumber
     * @param string|null $countryCode
     * @return User|null
     */
    public function findByPhone(?string $phoneNumber, ?string $countryCode): ?User
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
}
