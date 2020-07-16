<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Exceptions\InvalidPayloadException;
use App\Exceptions\Auth\PhoneNumberOccupiedException;
use App\Exceptions\TimeoutException;
use App\Http\Resources\SessionResource;
use App\Services\ApiService;
use App\Session;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService implements ApiService
{
    private AuthRequestService $authRequestService;
    private UserService $userService;

    /**
     * AuthService constructor.
     *
     * @param AuthRequestService $authRequestService
     * @param UserService $userService
     */
    public function __construct(AuthRequestService $authRequestService, UserService $userService)
    {
        $this->authRequestService = $authRequestService;
        $this->userService = $userService;
    }

    /**
     * Sends the verification code for further login/register.
     *
     * @param array $data
     * @param int $timeout
     *
     * @return AuthRequest
     *
     * @throws Exception
     */
    public function sendCode(array $data, int $timeout): AuthRequest
    {
        //Retrieving auth request
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);
        if (!is_null($authRequest)) $authRequest->delete();

        $code = 22222;

        return AuthRequest::create([
            'phone_number' => $data['phone_number'],
            'country_code' => $data['country_code'],
            'phone_code_hash' => Hash::make($code),// $code
            'timeout' => $timeout,
            'is_new' => !$this->userService->existsByPhone($data['phone_number'], $data['country_code']),
        ]);
    }

    /**
     * Registration in Messenger
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function signUp(array $data): array
    {
        $this->authRequestService->findByPhone($data['phone_number'], $data['country_code'])->delete();

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'country_code' => $data['country_code'],
            ]);

            return [
                'session' => Session::create([
                    'user_id' => $user->id,
                    'access_token_hash' => hash('sha256', (($accessToken = $user->id . '|' . sha1(random_bytes(100)) . sha1(random_bytes(100))))),
                ]),
                'access_token' => $accessToken,
            ];
        });
    }

    /**
     * Login in Messenger
     *
     * @param array $data
     * @return Session
     * @throws Exception
     */
    public function signIn(array $data): array
    {
        $user = $this->userService->findByPhone($data['phone_number'], $data['country_code']);

        $this->authRequestService->findByPhone($data['phone_number'], $data['country_code'])->delete();

        return [
            'session' => Session::create([
                'user_id' => $user->id,
                'access_token_hash' => hash('sha256', (($accessToken = $user->id . '|' . sha1(random_bytes(100)) . sha1(random_bytes(100))))),
            ]),
            'access_token' => $accessToken,
        ];
    }
}
