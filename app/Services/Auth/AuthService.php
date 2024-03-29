<?php

namespace App\Services\Auth;

use App\Models\AuthRequest;
use App\Exceptions\Auth\AuthRequestExpiredException;
use App\Exceptions\Auth\PhoneCodeHashInvalidException;
use App\Exceptions\Auth\PhoneCodeInvalidException;
use App\Exceptions\Auth\PhoneNumberOccupiedException;
use App\Exceptions\Auth\PhoneNumberUnoccupiedException;
use App\Exceptions\JWT\FingerprintInvalidException;
use App\Exceptions\JWT\RefreshTokenInvalidException;
use App\Services\User\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 * @package App\Services\Auth
 */
class AuthService
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
     * @return AuthRequest
     *
     * @throws Exception
     */
    public function sendCode(array $data): AuthRequest
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
            'phone_number' => $data['phone_number'],
            'country_code' => $data['country_code'],
            'phone_code_hash' => Hash::make($code),// $code
            'fingerprint' => $data['fingerprint'],
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
     * @param Request $request
     * @return array
     *
     * @throws AuthRequestExpiredException
     * @throws PhoneCodeHashInvalidException
     * @throws PhoneCodeInvalidException
     * @throws PhoneNumberOccupiedException
     */
    public function signUp(array $data, Request $request): array
    {
        // We obtain auth request to delete it before sign in
        // and to store fingerprint in session table for further
        // security checks
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        ##> Validation ##
        // Check for auth request expiration
        if ($this->userService
            ->existsByPhone($data['phone_number'], $data['country_code'])) {
            throw new PhoneNumberOccupiedException();
        }

        // Check if phone number is occupied
        if (is_null($authRequest)) {
            throw new AuthRequestExpiredException();
        }

        // Check if phone_code_hash is the same
        if ($authRequest->phone_code_hash !== $data['phone_code_hash']) {
            throw new PhoneCodeHashInvalidException();
        }

        // Check if the phone_code is correct using
        // phone_code_hash
        if (!Hash::check($data['phone_code'], $authRequest->phone_code_hash)) {
            throw new PhoneCodeInvalidException();
        }
        ##< Validation ##

        // Saving fingerprint from auth request
        $fingerprint = $authRequest->fingerprint;

        // We delete auth request as far as it is not necessary
        // to store it after successful sign in
        $authRequest->delete();

        return DB::transaction(function () use ($request, $data, $fingerprint) {
            $user = $this->userService->create([
                'name' => $data['name'],
                'username' => NULL,
                'phone_number' => $data['phone_number'],
                'country_code' => $data['country_code'],
            ]);

            $accessToken = auth()->login($user);

            return [
                'session' => $this->sessionService->create([
                    'user_id' => $user->id,
                    'fingerprint' => $fingerprint,
                    'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
                    'ua' => $request->userAgent(),
                    'ip' => $request->ip(),
                ]),
                'access_token' => $accessToken,
            ];
        });
    }

    /**
     * Sign in into Messenger.
     *
     * @param array $data
     * @param Request $request
     * @return array
     *
     * @throws AuthRequestExpiredException
     * @throws PhoneCodeHashInvalidException
     * @throws PhoneCodeInvalidException
     * @throws PhoneNumberUnoccupiedException
     */
    public function signIn(array $data, Request $request): array
    {
        // We obtain user in order to create session for him
        $user = $this->userService->findByPhone($data['phone_number'], $data['country_code']);

        // We obtain auth request to delete it before sign in
        // and to store fingerprint in session table for further
        // security checks
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        ##> Validation ##
        // Check if phone number is occupied
        if (is_null($user)) {
            throw new PhoneNumberUnoccupiedException();
        }

        // Check for auth request expiration
        if (is_null($authRequest)) {
            throw new AuthRequestExpiredException();
        }

        // Check if phone_code_hash is the same
        if ($authRequest->phone_code_hash !== $data['phone_code_hash']) {
            throw new PhoneCodeHashInvalidException();
        }

        // Check if the phone_code is correct using
        // phone_code_hash
        if (!Hash::check($data['phone_code'], $authRequest->phone_code_hash)) {
            throw new PhoneCodeInvalidException();
        }
        ##< Validation ##

        // Saving fingerprint from auth request
        $fingerprint = $authRequest->fingerprint;

        // We delete auth request as far as it is not necessary
        // to store it after successful sign in
        $authRequest->delete();

        // Login user and after that obtain JWT access token
        $accessToken = auth()->setTTL(1440)->login($user);

        return [
            'session' => $this->sessionService->create([
                'user_id' => $user->id,
                'fingerprint' => $fingerprint,
                'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
                'ua' => $request->userAgent(),
                'ip' => $request->ip(),
            ]),
            'access_token' => $accessToken,
        ];
    }

    /**
     * Refreshes tokens.
     *
     * @param array $data
     * @param Request $request
     * @return array
     *
     * @throws FingerprintInvalidException
     * @throws RefreshTokenInvalidException
     */
    public function refreshTokens(array $data, Request $request): array
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
                'fingerprint' => $fingerprint,
                'expires_in' => Carbon::now()->addDays(self::REFRESH_TOKEN_LIFETIME),
                'ua' => $request->userAgent(),
                'ip' => $request->ip(),
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
