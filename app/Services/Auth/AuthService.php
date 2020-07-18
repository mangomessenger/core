<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Exceptions\Auth\PhoneCodeHashInvalidException;
use App\Exceptions\Auth\PhoneCodeInvalidException;
use App\Exceptions\Auth\PhoneNumberOccupiedException;
use App\Exceptions\Auth\PhoneNumberUnoccupiedException;
use App\Exceptions\JWT\FingerprintInvalidException;
use App\Exceptions\JWT\RefreshTokenInvalidException;
use App\Services\ApiService;
use App\Session;
use App\User;
use App\Utils\RefreshTokenGenerator;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Propaganistas\LaravelPhone\PhoneNumber;

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
     * UserService instance
     *
     * @var SessionService
     */
    private SessionService $sessionService;

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
     * @param SessionService $sessionService
     */
    public function __construct(AuthRequestService $authRequestService,
                                UserService $userService,
                                SessionService $sessionService)
    {
        $this->authRequestService = $authRequestService;
        $this->userService = $userService;
        $this->sessionService = $sessionService;
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
        if (!is_null($authRequest)) {
            $authRequest->delete();
        }

        //Generating confirmation code
        $code = 22222;

        //Here we should send code via sms or via messenger

        // Creating auth request instance
        return $this->authRequestService->create([
            'phone_number' => PhoneNumber::make($data['phone_number'], $data['country_code'])->formatE164(),
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
     *
     * @throws AuthRequestExpiredException
     * @throws PhoneNumberOccupiedException
     * @throws PhoneCodeHashInvalidException
     * @throws PhoneCodeInvalidException
     */
    public function signUp(array $data): array
    {
        // We obtain auth request to delete it before sign in
        // and to store fingerprint in session table for further
        // security checks
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        if (is_null($authRequest)) {
            throw new AuthRequestExpiredException();
        }

        if ($this->userService
            ->existsByPhone($data['phone_number'], $data['country_code'])) {
            throw new PhoneNumberOccupiedException();
        }

        if ($authRequest->phone_code_hash !== $data['phone_code_hash']) {
            throw new PhoneCodeHashInvalidException();
        }

        if (!Hash::check($data['phone_code'], $authRequest->phone_code_hash)) {
            throw new PhoneCodeInvalidException();
        }

        // Saving fingerprint from auth request
        $fingerprint = $authRequest->fingerprint;

        // We delete auth request as far as it is not necessary
        // to store it after successful sign in
        $authRequest->delete();

        return DB::transaction(function () use ($data, $fingerprint) {
            $user = $this->userService->create([
                'name' => $data['name'],
                'phone_number' => PhoneNumber::make($data['phone_number'], $data['country_code'])->formatE164(),
                'country_code' => $data['country_code'],
            ]);

            $accessToken = auth()->login($user);

            return [
                'session' => $this->sessionService->create([
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
     *
     * @throws PhoneNumberUnoccupiedException
     * @throws PhoneCodeHashInvalidException
     * @throws PhoneCodeInvalidException
     * @throws AuthRequestExpiredException
     */
    public function signIn(array $data): array
    {
        // We obtain user in order to create session for him
        $user = $this->userService->findByPhone($data['phone_number'], $data['country_code']);

        if (is_null($user)) {
            throw new PhoneNumberUnoccupiedException();
        }

        // We obtain auth request to delete it before sign in
        // and to store fingerprint in session table for further
        // security checks
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        if (is_null($authRequest)) throw new AuthRequestExpiredException();

        if ($authRequest->phone_code_hash !== $data['phone_code_hash']) {
            throw new PhoneCodeHashInvalidException();
        }

        if (!Hash::check($data['phone_code'], $authRequest->phone_code_hash)) {
            throw new PhoneCodeInvalidException();
        }

        // Saving fingerprint from auth request
        $fingerprint = $authRequest->fingerprint;

        // We delete auth request as far as it is not necessary
        // to store it after successful sign in
        $authRequest->delete();

        // Login user and after that obtain JWT access token
        $accessToken = auth()->login($user);

        return [
            'session' => $this->sessionService->create([
                'user_id' => $user->id,
                'refresh_token' => RefreshTokenGenerator::generate(),
                'fingerprint' => $fingerprint,
                'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
            ]),
            'access_token' => $accessToken,
        ];
    }

    /**
     * Refreshes tokens.
     *
     * @param array $data
     * @return array
     *
     * @throws FingerprintInvalidException
     * @throws RefreshTokenInvalidException
     * @throws Exception
     */
    public function refreshTokens(array $data): array
    {
        // Obtaining current session by refresh_token
        // to delete refresh it
        $session = $this->sessionService->firstWhere('refresh_token', $data['refresh_token']);

        // If session does not exist we return
        // REFRESH_TOKEN_INVALID error
        if (is_null($session)) throw new RefreshTokenInvalidException();

        // Saving fingerprint from obtained session
        $fingerprint = $session->fingerprint;

        // We invalidate current session
        $session->delete();

        // If fingerprints differentiate we return
        // FINGERPRINT_INVALID error
        // Note: Session is already invalidated
        if ($fingerprint !== $data['fingerprint']) throw new FingerprintInvalidException();

        // We obtain new access token
        $accessToken = auth()->login($this->userService->find($session->user_id));

        return [
            'session' => $this->sessionService->create([
                'user_id' => $session->user_id,
                'refresh_token' => RefreshTokenGenerator::generate(),
                'fingerprint' => $fingerprint,
                'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
            ]),
            'access_token' => $accessToken,
        ];
    }

    /**
     * Logout from session
     *
     * @param array $data
     * @return void
     * @throws RefreshTokenInvalidException
     */
    public function logout(array $data): void
    {
        // Obtaining current session by refresh_token
        $session = $this->sessionService->firstWhere('refresh_token', $data['refresh_token']);

        // If session does not exist we return
        // REFRESH_TOKEN_INVALID error
        if (is_null($session)) throw new RefreshTokenInvalidException();

        // We invalidate current session
        $session->delete();
    }
}
