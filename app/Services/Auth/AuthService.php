<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Services\ApiService;
use App\Session;
use App\User;
use App\Utils\RefreshTokenGenerator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 * @package App\Services\Auth
 */
class AuthService implements ApiService
{
    /**
     * AuthRequestService instance
     *
     * @var AuthRequestService
     */
    private AuthRequestService $authRequestService;

    /**
     * UserService instance
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * Auth request lifetime in DAYS.
     *
     * @var int REFRESH_TOKEN_LIFETIME
     */
    public const AUTH_REQUEST_LIFETIME = 1; // 1 Day
    /**
     * Refresh token lifetime in DAYS.
     *
     * @var int REFRESH_TOKEN_LIFETIME
     */
    public const REFRESH_TOKEN_LIFETIME = 30; // 30 Days

    /**
     * AuthService constructor.
     *
     * @param AuthRequestService $authRequestService
     * @param UserService $userService
     */
    public function __construct(AuthRequestService $authRequestService,
                                UserService $userService)
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
        // We obtain auth request in order to delete
        // it in case it is already exists in database
        // by given phone number
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        // If auth request exists we delete it
        // because we are going to create new one
        if (!is_null($authRequest)) $authRequest->delete();

        //Generating confirmation code
        $code = 22222;

        //Here we should send code via sms or via messenger

        // Creating auth request instance
        return AuthRequest::create([
            'phone_number' => $data['phone_number'],
            'country_code' => $data['country_code'],
            'phone_code_hash' => Hash::make($code),// $code
            'fingerprint' => $data['fingerprint'],
            'timeout' => $timeout,
            'is_new' => !$this->userService->existsByPhone(
                $data['phone_number'],
                $data['country_code']
            ), //Checking if user already exists
        ]);
    }

    /**
     * Registration in Messenger.
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function signUp(array $data): array
    {
        // We obtain auth request to delete it before sign in
        // and to store fingerprint in session table for further
        // security checks
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        // Saving fingerprint from auth request
        $fingerprint = $authRequest->fingerprint;

        // We delete auth request as far as it is not necessary
        // to store it after successful sign in
        $authRequest->delete();

        return DB::transaction(function () use ($data, $fingerprint) {
            $user = User::create([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'country_code' => $data['country_code'],
            ]);

            $accessToken = auth()->login($user);

            return [
                'session' => Session::create([
                    'user_id' => $user->id,
                    'refresh_token' => RefreshTokenGenerator::generate(),
                    'fingerprint' => $fingerprint,
                    'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
                ]),
                'access_token' => $accessToken,
            ];
        });
    }

    /**
     * Sign in into Messenger.
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function signIn(array $data): array
    {
        // We obtain user in order to create session for him
        $user = $this->userService->findByPhone($data['phone_number'], $data['country_code']);

        // We obtain auth request to delete it before sign in
        // and to store fingerprint in session table for further
        // security checks
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        // Saving fingerprint from auth request
        $fingerprint = $authRequest->fingerprint;

        // We delete auth request as far as it is not necessary
        // to store it after successful sign in
        $authRequest->delete();

        // Login user and after that obtain JWT access token
        $accessToken = auth()->login($user);

        return [
            'session' => Session::create([
                'user_id' => $user->id,
                'refresh_token' => RefreshTokenGenerator::generate(),
                'fingerprint' => $fingerprint,
                'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
            ]),
            'access_token' => $accessToken,
        ];
    }
}
