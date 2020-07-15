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
     * @throws TimeoutException
     */
    public function sendCode(array $data, int $timeout): AuthRequest
    {
        //Retrieving user
        $user = $this->userService->findByPhone($data['phone_number'], $data['country_code']);

        //Retrieving auth request
        $authRequest = $this->authRequestService->findByPhone($data['phone_number'], $data['country_code']);

        //Checking if phone number had auth attempt before
        if (!is_null($authRequest)) {
            $expireTime = $authRequest->created_at->addSeconds($timeout);
            $now = Carbon::now();

            // Checks if auth request is in timeout
            if ($expireTime > $now) {
                $timeout = $now->diffInSeconds($expireTime);

                throw new TimeoutException("A wait of {$timeout} seconds is required.", $timeout);
            } else {
                //Deletes auth request if one exists
                $authRequest->delete();
            }
        }

        //Generating code
        //        $code = rand(10000, 99999);
        $code = 22222;
        //        CodeService::sendCode($request->get('phone'));

        return AuthRequest::create([
            'phone_number' => $data['phone_number'],
            'country_code' => $data['country_code'],
            'phone_code_hash' => Hash::make($code),// $code
            'timeout' => $timeout,
            'is_new' => $user === NULL,
        ]);
    }

    /**
     * @param array $data
     * @return Session
     * @throws Exception
     */
    public function signUp(array $data): Session
    {
        $this->authRequestService->findByPhone($data['phone_number'], $data['country_code'])->delete();

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'country_code' => $data['country_code'],
            ]);

            return Session::create([
                'user_id' => $user->id,
                'access_token' => sha1(random_bytes(100)) . sha1(random_bytes(100)),
            ]);
        });
    }

}
