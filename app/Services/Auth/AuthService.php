<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Exceptions\TimeoutException;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService implements ApiService
{

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
        $user = \App\User::firstWhere('phone', $data['phone']);

        //Retrieving auth request
        $authRequest = AuthRequest::firstWhere('phone', $data['phone']);

        //Checking if phone number had auth attempt before
        if ($authRequest !== NULL) {
            $expireTime = $authRequest->created_at->addSeconds($timeout);
            $now = Carbon::now();

            // Checks if auth request is in timeout
            if ($expireTime > $now) {
                $timeout = $now->diffInSeconds($expireTime);

                throw new TimeoutException("A wait of {$timeout} seconds is required", $timeout);
            } else {
                //Deletes auth request if one exists
                $authRequest->delete();
            }
        }

        //Generating code
        $code = rand(10000, 99999);
        //        CodeService::sendCode($request->get('phone'));

        return AuthRequest::create([
            'phone' => $data['phone'],
            'country_code' => $data['country_code'],
            'phone_code_hash' => Hash::make($code),// $code
            'timeout' => $timeout,
            'is_new' => $user === NULL,
        ]);
    }

}
